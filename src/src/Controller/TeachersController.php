<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\SessionHelper;

/**
 * Teachers Controller
 *
 * @property \App\Model\Table\TeachersTable $Teachers
 */
class TeachersController extends AppController
{
	
	private $_userInfo;
	private $_userRoles;

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		
		$this->_userInfo = $this->request->session()->read('UserInfo');
		$roles = array();
		
		foreach($this->_userInfo->teacher->roles as $r) {
			$roles[] = $r->type;
		}
		
		$this->_userRoles = $roles;
    }
	
	public function isAuthorized($user)
	{
		return true; //remove line on production
		
		//Only admin or teacher itself can edit the teacher
		if (in_array($this->request->action, ['edit', 'allocateClazzes'])) {
			$teacherId = (int)$this->request->params['pass'][0];

			if ($user['id'] === $teacherId || $user['is_admin'] || in_array('COORDINATOR', $this->_userRoles)) {
				return true;
			}
			
			$this->Flash->warning(__('Você não tem permissão para editar esse docente.'));
			return false;
		}
		
		return parent::isAuthorized($user);
	}

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {		
	
		$this->set('teachers', $this->paginate($this->Teachers->find('all')->contain(['Users'])));
	
		if (!in_array('COORDINATOR', $this->_userRoles)) {
			
			$this->set('teachers', $this->paginate($this->Teachers->find('all')
				->contain(['Users' ])
				->innerJoinWith('Users', function($q) { 
					return $q->where(['Users.id' => $this->_userInfo->id]);
				})
			));
		} 

		$this->set('_serialize', ['teachers']);
    }

    /**
     * View method
     *
     * @param string|null $id Teacher id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => ['Users', 'Clazzes', 'Clazzes.Subjects'
			, 'Clazzes.Locals', 'Clazzes.Processes', 'Knowledges', 'Roles', 'Roles.Knowledges']
        ]);
        $this->set('teacher', $teacher);
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	$teacher = $this->Teachers->newEntity();
		$this->loadModel('Knowledges');
        $knowledges = $this->Knowledges->find('list',array('fields'=>array('id','name')));

		if ($this->request->is('post')) {

			$data = $this->request->data;
			$data['user']['is_admin'] = isset($this->request->data['user']['is_admin']) ? 1 : 0;

			$teacher = $this->Teachers->patchEntity($teacher, $data, [
				'associated' => ['Users' => ['validate' => 'default'], 'Knowledges']
			]);

            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));
                return $this->redirect(['action' => 'edit', $teacher->id]);
            } else {
                $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('teacher'));

        
        $this->set(compact('knowledges'));
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Teacher id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => ['Users', 'Knowledges'
				, 'Clazzes'
				, 'Clazzes.Subjects'
				, 'Clazzes.Subjects.Knowledges'
				, 'Clazzes.Subjects.Courses'
			]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

			$data = $this->request->data;
			$data['user']['is_admin'] = isset($this->request->data['user']['is_admin']) ? 1 : 0;

			if (!empty($this->request->data['pwd'])) {
				$data['user']['password'] = $data['pwd'];
				unset($data['pwd']);
			}

			$teacher = $this->Teachers->patchEntity($teacher, $data, [
				'associated' => ['Users' => ['validate' => 'default'], 'Knowledges']
			]);

            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
            }
        }

        $this->loadModel('Knowledges');
        $knowledges = $this->Knowledges->find('list', array('fields' => array('id', 'name')));
		
		$this->set(compact('knowledges'));
        $this->set(compact('teacher'));
        $this->set('_serialize', ['teacher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teacher = $this->Teachers->get($id);
        if ($this->Teachers->delete($teacher)) {
            $this->Flash->success(__('The teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The teacher could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


	/**
     * Allocate Clazzes method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
	public function allocateClazzes($id = null, $clazz_id = null, $allocate = false)
	{
		$table_clazzes_teachers = TableRegistry::get('ClazzesTeachers');
		$table_processes = TableRegistry::get('Processes');

		$teacher = $this->Teachers->get($id, [
            'contain' => ['Users'
				, 'Clazzes'
				, 'Clazzes.Subjects'
				, 'Clazzes.Subjects.Knowledges'
				, 'Clazzes.Subjects.Courses'
			]
        ]);

		$processes = $table_processes->find('list')
            ->where(['initial_date <= ' => 'CURDATE()', 'final_date >= ' => 'CURDATE()'])
            ->orWhere(['status' => 'OPENED'])
            ->toArray();

        $processes = array_replace(['' => __('[Selecione]')], $processes);

		if (count($processes) < 2) {

			$this->Flash->info(__('Não existe nenhum Processo de Distribuição de Disciplinas aberto.'));
			$this->set('process_exists', false);
			$this->set('_serialize', ['process_exists']);

			$this->set(compact('teacher'));
			$this->set('_serialize', ['teacher']);
			$this->set('clazzes', array());
			$this->set('_serialize', ['clazzes']);
			$this->set('processes', array());
			$this->set('_serialize', ['processes']);

		} else {

			$clazzes = $this->getClazzes();

			if ($this->RequestHandler->accepts('ajax')) {

				$this->response->disableCache();

				if ($id != null && $clazz_id != null && $allocate == 'allocate') {

					$query = $table_clazzes_teachers->query();
					$query->delete()->where([
							'clazz_id' => $clazz_id,
							'teacher_id' => $id
					])->execute();

					$query = $table_clazzes_teachers->query();
					$query->insert(['clazz_id', 'teacher_id'])->values([
							'clazz_id' => $clazz_id,
							'teacher_id' => $id
						])->execute();

					if ($query) {
						echo 'success';
					} else {
						echo 'error';
					}

					die();
				} else if ($id != null && $clazz_id != null && $allocate == 'deallocate') {

					$query = $table_clazzes_teachers->query();
					$query->delete()->where([
							'clazz_id' => $clazz_id,
							'teacher_id' => $id
					])->execute();

					if ($query) {
						echo 'success';
					} else {
						echo 'error';
					}
					die();
				}

			}

			/* Filters */
			if ($this->request->is('post')) {
				$data = $this->request->data;
				$clazzes = $this->getClazzes($data);
				echo json_encode($clazzes);
				die();
			}

			$this->set(compact('teacher'));
			$this->set('_serialize', ['teacher']);
			$this->set('clazzes', $clazzes);
			$this->set('_serialize', ['clazzes']);
			$this->set('processes', $processes);
			$this->set('_serialize', ['processes']);
			$this->set('process_exists', true);
			$this->set('_serialize', ['process_exists']);
		}


	}


	/**
     * Get Clazzes method
     *
     * @param array|null $params Filters.
     * @return paginated data.
     */

    private function getClazzes($params = null) 
    {	
	
		$this->loadModel('Clazzes');
	
		$data = $this->Clazzes->find('all')
            ->contain([
                'Subjects.Courses', 'Subjects.Knowledges',
                'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules',
                'Processes'
        ]);

        if($params !== null) {

			$data = $this->Clazzes->find('all')
				->contain([
					'Subjects' => function ($q) use ($params) {
						return $q->where(['Subjects.name LIKE ' => '%' . $params['subject_name'] . '%']);
					},
					'Subjects.Courses' => function ($q) use ($params) {
						return $q->where(['Courses.name LIKE ' => '%' . $params['course_name'] . '%']);
					}, 
					'Subjects.Knowledges' => function ($q) use ($params) {
						return $q->where(['Knowledges.name LIKE ' => '%' . $params['knowledge_name'] . '%']);
					},
					'Processes' => function ($q) use ($params) {
						return $q->where(['Clazzes.process_id LIKE ' => '%' . $params['process'] . '%']);
					},
					'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules'
				])
				->innerJoinWith('ClazzesSchedulesLocals.Locals', function ($q) use ($params) {
						return $q->where(['Locals.name LIKE ' => '%' . $params['local'] . '%'])
								->orWhere(['Locals.address LIKE ' => '%' . $params['local'] . '%']);
					})
				->innerJoinWith('ClazzesSchedulesLocals.Schedules', function ($q) use ($params) {
						return $q->where(['Schedules.start_time >= ' => $params['start_time']['hour'] . ':' . $params['start_time']['minute'],
								'Schedules.end_time <= ' => $params['end_time']['hour'] . ':' . $params['end_time']['minute']]);
					});
					
			if (!empty($params['week_day'])) {
				$data->where(['week_day' => $params['week_day']]);
			}
					
				
			$data->group(['Clazzes.id']);

        }
		
		return $data->all();
	}
}

