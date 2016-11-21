<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Liabilities Controller
 *
 * @property \App\Model\Table\LiabilitiesTable $Liabilities
 */
class LiabilitiesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Fks']
        ];
        $liabilities = $this->paginate($this->Liabilities);

        $this->set(compact('liabilities'));
        $this->set('_serialize', ['liabilities']);
    }

    /**
     * View method
     *
     * @param string|null $id Liability id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $liability = $this->Liabilities->get($id, [
            'contain' => ['Fks']
        ]);

        $this->set('liability', $liability);
        $this->set('_serialize', ['liability']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $liability = $this->Liabilities->newEntity();
        if ($this->request->is('post')) {
            $liability = $this->Liabilities->patchEntity($liability, $this->request->data);
            if ($this->Liabilities->save($liability)) {
                $this->Flash->success(__('The liability has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The liability could not be saved. Please, try again.'));
            }
        }
        $fks = $this->Liabilities->Fks->find('list', ['limit' => 200]);
        $this->set(compact('liability', 'fks'));
        $this->set('_serialize', ['liability']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Liability id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $liability = $this->Liabilities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $liability = $this->Liabilities->patchEntity($liability, $this->request->data);
            if ($this->Liabilities->save($liability)) {
                $this->Flash->success(__('The liability has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The liability could not be saved. Please, try again.'));
            }
        }
        $fks = $this->Liabilities->Fks->find('list', ['limit' => 200]);
        $this->set(compact('liability', 'fks'));
        $this->set('_serialize', ['liability']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Liability id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $liability = $this->Liabilities->get($id);
        if ($this->Liabilities->delete($liability)) {
            $this->Flash->success(__('The liability has been deleted.'));
        } else {
            $this->Flash->error(__('The liability could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
