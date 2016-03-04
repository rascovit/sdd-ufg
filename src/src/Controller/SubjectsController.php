<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Subjects Controller
 *
 * @property \App\Model\Table\SubjectsTable $Subjects
 */
class SubjectsController extends AppController
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
                'Subjects.id' => 'ASC'
            ]
        ];

        $this->request->data = $this->request->query;
        $findByFilters = $this->Subjects->findByFilters($this->request->query);
        $subjects = $findByFilters['data'];

        $this->set('isFiltered', $findByFilters['isFiltered']);
        $this->set('courses', array_replace([0 => __('[Selecione]')], $this->Subjects->Courses->find('list')->toArray()));
        $this->set('knowledges', array_replace([0 => __('[Selecione]')], $this->Subjects->Knowledges->find('list')->toArray()));

        $this->set('subjects', $this->paginate($subjects));
        $this->set('_serialize', ['subjects']);
    }

    /**
     * View method
     *
     * @param string|null $id Subject id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $subject = $this->Subjects->get($id, [
            'contain' => [
                'Knowledges', 'Courses',
                'Clazzes.Processes'
            ]
        ]);

        $clazzes = [0];
        foreach($subject->clazzes as $clazz) {
            $clazzes[] = $clazz->id;
        }

        $clazzesTeachersModel = TableRegistry::get('ClazzesTeachers');
        $experiencedTeachers = $clazzesTeachersModel->find()->contain(['Teachers.Users'])
            ->where([
                'status' => 'SELECTED',
                'clazz_id IN' => $clazzes
            ]);

        $this->set('subject', $subject);
        $this->set('experiencedTeachers', $experiencedTeachers);
        $this->set('_serialize', ['subject']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $subject = $this->Subjects->newEntity();
        if ($this->request->is('post')) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->data);
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('Disciplina adicionada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível adicionar a disciplina, tente novamente.'));
            }
        }

        $this->set('knowledges', array_replace([0 => __('[Selecione]')],
            $this->Subjects->Knowledges->find('list')->toArray()));
        $this->set('courses', array_replace([0 => __('[Selecione]')],
            $this->Subjects->Courses->find('list')->toArray()));
        $this->set(compact('subject'));
        $this->set('_serialize', ['subject']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Subject id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $subject = $this->Subjects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->data);
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('Disciplina modificada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível modificar a disciplina, tente novamente.'));
            }
        }

        $this->set('knowledges', array_replace([0 => __('[Selecione]')],
            $this->Subjects->Knowledges->find('list')->toArray()));
        $this->set('courses', array_replace([0 => __('[Selecione]')],
            $this->Subjects->Courses->find('list')->toArray()));
        $this->set(compact('subject'));
        $this->set('_serialize', ['subject']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Subject id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $subject = $this->Subjects->get($id);
        if ($this->Subjects->delete($subject)) {
            $this->Flash->success(__('Disciplina removida com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível remover a disciplina, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
