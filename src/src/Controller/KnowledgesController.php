<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Knowledges Controller
 *
 * @property \App\Model\Table\KnowledgesTable $Knowledges
 */
class KnowledgesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 25,
            'order' => [
                'Knowledges.id' => 'ASC'
            ]
        ];

        $this->request->data = $this->request->query;
        $knowledges = $this->Knowledges->findByFilters($this->request->query);

        $this->set('knowledges', $this->paginate($knowledges));
        $this->set('_serialize', ['knowledges']);
    }

    /**
     * View method
     *
     * @param string|null $id Knowledge id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $knowledge = $this->Knowledges->get($id, [
            'contain' => ['Roles.Teachers.Users', 'Subjects.Courses']
        ]);
        $this->set('knowledge', $knowledge);
        $this->set('_serialize', ['knowledge']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $knowledge = $this->Knowledges->newEntity();
        if ($this->request->is('post')) {
            $knowledge = $this->Knowledges->patchEntity($knowledge, $this->request->data);
            if ($this->Knowledges->save($knowledge)) {
                $this->Flash->success(__('Núcleo de conhecimento adicionado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível adicionar núcleo de conhecimento, tente novamente.'));
            }
        }

        $this->set(compact('knowledge'));
        $this->set('_serialize', ['knowledge']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Knowledge id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $knowledge = $this->Knowledges->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $knowledge = $this->Knowledges->patchEntity($knowledge, $this->request->data);
            if ($this->Knowledges->save($knowledge)) {
                $this->Flash->success(__('Núcleo de conhecimento modificado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível modificar o núcleo de conhecimento, tente novamente.'));
            }
        }
        $this->set(compact('knowledge'));
        $this->set('_serialize', ['knowledge']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Knowledge id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $knowledge = $this->Knowledges->get($id);
        if ($this->Knowledges->delete($knowledge)) {
            $this->Flash->success(__('Núcleo de conhecimento removido com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível remover o núcleo de conhecimento, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
