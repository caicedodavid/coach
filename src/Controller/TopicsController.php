<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Topics Controller
 *
 * @property \App\Model\Table\TopicsTable $Topics
 */
class TopicsController extends AppController
{

    /**
     * List all of the coach topics
     *
     * @return \Cake\Network\Response|null
     */
    public function coachTopics($id =null)
    {
        $userId = $id ? $id: $this->getUser()['id'];
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'topics' => ['coachId' => $userId]
            ],
            'order' =>[
                'Topics.name' => 'asc'
            ]
        ];
        $topics = $this->paginate($this->Topics);

        $this->set(compact('topics'));
        $this->set('_serialize', ['topics']);

        if ($this->request->is('ajax')) {
            $this->render('list');
        }
    }

    /**
     * user view of all of the coach topics 
     *
     * @return \Cake\Network\Response|null
     */
    public function coachTopicsUser($id = null)
    {
        $this->coachTopics($id);
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
        $topic = $this->Topics->get($id, [
            'contain' => ['TopicImage','Coach']
        ]);

        $this->set('topic', $topic);
        $this->set('_serialize', ['topic']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $topic = $this->Topics->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $topic["coach_id"] = $this->getUser()["id"];
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
}
