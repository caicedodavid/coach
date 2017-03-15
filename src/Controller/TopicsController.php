<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;
use App\Error\AssociatedTopicException;
use Cake\Core\Configure;

/**
 * Topics Controller
 *
 * @property \App\Model\Table\TopicsTable $Topics
 */
class TopicsController extends AppController
{   
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public $helpers = ['PlumSearch.Search'];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index','coachTopics','view']);
        $this->loadComponent('PlumSearch.Filter', [
            'parameters' => [
                [
                    'name' => 'category_id',
                    'className' => 'Select',
                    'finder' => $this->Topics->Categories->find('list'),
                ]
            ]
        ]);
    }

    /**
     * Before render method
     *
     * @param $event Event object 
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);

    }

    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->config('updateImage', ['updateImage']);
        $this->Auth->allow('updateImage');
        $this->eventManager()->off($this->Csrf);
    }

    /**
     * List all of the public topics
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 6,
        ];
        $query = $this->Topics->find('indexTopics');
        $topics = $this->paginate($this->Filter->prg($query));
        $categories = $this->Topics->Categories->find('visibleCategories');
        $this->set(compact('topics','categories'));
        $this->set('_serialize', ['topics']);
        if ($this->request->is('ajax')) {
            $this->render('list');
        }
    }


    /**
     * List all of the coach topics
     *
     * @return \Cake\Network\Response|null
     */
    public function coachTopics($id = null)
    {
        $user = $this->getUser();

        if ($id === $user['id']) {
            $this->profileTopics($id);
        }
        else{
            $this->publicTopicsByCoach($id);
        }
        $user = $this->AppUsers->get($id, [
            'contain' => ['UserImage']
        ]);
    }

    /**
     * user view of all of the coach topics 
     *
     * @return \Cake\Network\Response|null
     */
    public function publicTopicsByCoach($id)
    {
        $this->loadModel('AppUsers');
        $user = $this->AppUsers->get($id, [
            'contain' => ['UserImage']
        ]);
        $this->paginate = [
            'limit' => 6,
            'finder' => [
                'publicTopicsByCoach' => ['coachId' => $id]
            ],
            'order' => [
                'Topics.name' => 'asc'
            ]
        ];
        $topics = $this->paginate($this->Topics);

        $this->set('user', $user);
        $this->set(compact('topics'));
        $this->set('_serialize', ['topics','user']);

    }

    /**
     * user view of all of the coach topics 
     *
     * @return \Cake\Network\Response|null
     */
    public function profileTopics($id)
    {
        $this->loadModel('AppUsers');
        $user = $this->AppUsers->get($id, [
            'contain' => ['UserImage']
        ]);
        $this->paginate = [
            'limit' => 6,
            'finder' => [
                'topicsByCoach' => ['coachId' => $id]
            ],
            'order' => [
                'Topics.name' => 'asc'
            ]
        ];
        $topics = $this->paginate($this->Topics);
        $this->set('user', $user);
        $this->set(compact('topics'));
        $this->set('_serialize', ['topics','user']);
    }

    /**
     * View method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $topic = $this->Topics->find('topicCoach', [
            'id' => $id
        ])
        ->first();;
        $this->set('isCoach', $this->isCoach($this->getUser()));
        $this->set('topic', $topic);
        $this->set('_serialize', ['topic']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($userId = null)
    {
        $topic = $this->Topics->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if(!$data['categories']['_ids']) {
                $this->Flash->error(__('You must select at least one category.'));
            } else {
                $topic = $this->Topics->patchTopic($userId, $topic, $data);
                if ($this->Topics->save($topic)) { 
                    if(!$this->Topics->saveImage(array('file' => $data['avatar_file'], 'data' => $data['avatar_data']), $topic->id)) {
                        $this->Flash->error(__('There was a problem saving the image.'));
                    };     
                    $this->Flash->success(__('The topic has been saved.'));
                    return $this->redirect(['action' => 'coachTopics', $userId]);
                } else {
                    $this->Flash->error(__('The topic could not be saved. Please, try again.'));
                }
            } 
        }
        $categories = $this->Topics->Categories->find('list');
        $this->set('times', $this->Topics->getDurationArray());
        $this->set(compact('topic', 'categories'));
        $this->set('_serialize', ['topic']);
        $this->set('ratio', Configure::read('FileStorage.imageSizes.Topics.big.thumbnail.width')/Configure::read('FileStorage.imageSizes.Topics.big.thumbnail.height'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $topic = $this->Topics->find('containImageCategories', ['topicId' => $id])
            ->first();
        $topicCategories = Hash::extract($topic->categories, '{n}.id');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            if(!$data['categories']['_ids']) {
                $this->Flash->error(__('You must select at least one category'));
            } else {
                $topic = $this->Topics->patchEntity($topic, $data);
                $topic->dirty('categories', true);
                if ($this->Topics->save($topic)) {
                    if(!$this->Topics->saveImage(array('file' => $data['avatar_file'], 'data' => $data['avatar_data']), $topic->id)) {
                        $this->Flash->error(__('There was a problem saving the image.'));
                    };    
                    $this->Flash->success(__('The topic has been saved.'));
    
                    return $this->redirect(['action' => 'view', $id]);
                } else {
                    $this->Flash->error(__('The topic could not be saved. Please, try again.'));
                }
            }
        }
        $categories = $this->Topics->Categories->find('list');
        $this->set('times', $this->Topics->getDurationArray());
        $this->set(compact('topic', 'categories', 'topicCategories'));
        $this->set('_serialize', ['topic']);
        $this->set('ratio', Configure::read('FileStorage.imageSizes.Topics.big.thumbnail.width')/
            Configure::read('FileStorage.imageSizes.Topics.big.thumbnail.height'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        try {
            $topic = $this->Topics->get($id);
            if ($this->Topics->delete($topic)) {
                $this->Flash->success(__('The topic has been deleted.'));
                return $this->redirect(['action' => 'coachTopics', $this->getUser()['id'], 'controller' => 'Topics']);
            } else {
                 $this->Flash->error(__('The topic could not be deleted. Please, try again.'));          
            }
        } catch (AssociatedTopicException $e) {
            $this->Flash->error($e->getMessage());
            return $this->redirect(['action' => 'view', $topic->id]);
        }
    }
}
