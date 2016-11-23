<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index','coachTopics','view']);
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
     * List all of the public topics
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 6,
            'finder' => 'indexTopics'
        ];
        $topics = $this->paginate($this->Topics);
        $this->set(compact('topics'));
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
        $id  = $id? $id: $user['id'];
        if($this->isCoach($user)) {
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
            $topic["coach_id"] = $userId;
            if(!$data["topic_image"]["file"]["size"]){
                unset($data["topic_image"]);
            }
            $topic = $this->Topics->patchEntity($topic, $data);
            if ($this->Topics->save($topic)) {
                $this->Flash->success(__('The topic has been saved.'));

                return $this->redirect(['action' => 'coachTopics']);
            } else {
                $this->Flash->error(__('The topic could not be saved. Please, try again.'));
            }
        }
        $this->set('times',$this->getDurationArray());
        $this->set(compact('topic'));
        $this->set('_serialize', ['topic']);
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
        $topic = $this->Topics->get($id, [
            'contain' => ['TopicImage']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            //$data['duration'] = (int)$data['duration'];
            debug($data['duration']);
            if(!$data["topic_image"]["file"]["size"]){
                unset($data["topic_image"]);
            }
            $topic = $this->Topics->patchEntity($topic, $data);
            if ($this->Topics->save($topic)) {
                $this->Flash->success(__('The topic has been saved.'));

                return $this->redirect(['action' => 'coachTopics']);
            } else {
                $this->Flash->error(__('The topic could not be saved. Please, try again.'));
            }
        }
        $this->set('times',$this->getDurationArray());
        $this->set(compact('topic'));
        $this->set('_serialize', ['topic']);
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
        $this->request->allowMethod(['post', 'delete']);
        $topic = $this->Topics->get($id);
        if ($this->Topics->delete($topic)) {
            $this->Flash->success(__('The topic has been deleted.'));
        } else {
            $this->Flash->error(__('The topic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    /**
     * Making an arary for selection the duration of a cless when adding a topic
     *
     * @return Array
     */
    public function getDurationArray()
    {
        $array = array();
        for ($i = 1; $i <= 6; $i++) {
            $array[$i*10] = ((string)$i*10) . ' min';
        }
        return $array;
    }

}
