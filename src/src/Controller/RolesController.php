<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 */
class RolesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Knowledges', 'Teachers', 'Teachers.Users']
        ];
        $this->set('roles', $this->paginate($this->Roles));
        $this->set('_serialize', ['roles']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEntity();
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->data);
            if ($role->type == 'COORDINATOR') {
                $role->knowledge_id = null;
                $role->knowledge = null;
            }
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('O papel foi salvo.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('O papel não pôde ser salvo. Por favor, tente novamente.'));
            }
        }
        $this->set('knowledges', $this->Roles->Knowledges->find('list'));
        $this->Roles->Teachers->displayField('display_field');
        $this->set('teachers', $this->Roles->Teachers->find('list', array('contain' => ['Users'])));
        $this->set('roleTypes', array('FACILITATOR' => 'Facilitador', 'COORDINATOR' => 'Coordenador'));

        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        if ($this->Roles->delete($role)) {
            $this->Flash->success(__('O papel foi deletado!'));
        } else {
            $this->Flash->error(__('O papel não pôde ser deletado. Por favor, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
