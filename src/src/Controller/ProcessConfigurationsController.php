<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProcessConfigurations Controller
 *
 * @property \App\Model\Table\ProcessConfigurationsTable $ProcessConfigurations
 */
class ProcessConfigurationsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => []
        ];
        $this->set('processConfigurations', $this->paginate($this->ProcessConfigurations));
        $this->set('_serialize', ['processConfigurations']);
    }

    /**
     * View method
     *
     * @param string|null $id Process Configuration id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $processConfiguration = $this->ProcessConfigurations->get($id, [
            'contain' => []
        ]);
        $this->set('processConfiguration', $processConfiguration);
        $this->set('_serialize', ['processConfiguration']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $processConfiguration = $this->ProcessConfigurations->newEntity();
        if ($this->request->is('post')) {
            $processConfiguration = $this->ProcessConfigurations->patchEntity($processConfiguration, $this->request->data);
            if ($this->ProcessConfigurations->save($processConfiguration)) {
                $this->Flash->success(__('A configuração de processo foi salva.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('A configuração de processo nao pode ser salva. Tente novamente.'));
            }
        }
        $this->set(compact('processConfiguration'));
        $this->set('_serialize', ['processConfiguration']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Process Configuration id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $processConfiguration = $this->ProcessConfigurations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $processConfiguration = $this->ProcessConfigurations->patchEntity($processConfiguration, $this->request->data);
            if ($this->ProcessConfigurations->save($processConfiguration)) {
                $this->Flash->success(__('A configuração de processo foi salva.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('A configuração de processo nao pode ser salva. Tente novamente.'));
            }
        }
        $this->set(compact('processConfiguration'));
        $this->set('_serialize', ['processConfiguration']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Process Configuration id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $processConfiguration = $this->ProcessConfigurations->get($id);
        if ($this->ProcessConfigurations->delete($processConfiguration)) {
            $this->Flash->success(__('A configuração de processo foi apagada.'));
        } else {
            $this->Flash->error(__('A configuração de processo nao pode ser apagada. Tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
