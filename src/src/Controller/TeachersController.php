<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\KnowledgesTeacher;
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
	public function isAuthorized($user)
	{
		if($this->request->action == 'view') {
			return true;
		}

		if (in_array($this->request->action, ['edit', 'allocateClazzes'])) {
			$teacherId = (int)$this->request->params['pass'][0];

			if ($this->loggedUser->teacher->id === $teacherId || $this->loggedUser->canAdmin()) {
				return true;
			}

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
        $nameFilter = '%';
        if ($this->request->query && $this->request->query['name']) {
            $nameFilter = '%' . $this->request->query['name'] . '%';
        }
		if ($this->loggedUser->canAdmin()) {
		    $this->set('teachers', $this->paginate($this->Teachers->find('all',
                ['conditions' => ['Users.name like' => $nameFilter]])
                ->contain(['Users'])));
        } else {
            $this->set('teachers', $this->paginate($this->Teachers->find('all',
                            ['conditions' => ['Users.name like' => $nameFilter, 'Teachers.id' => $this->loggedUser->teacher->id]])
                            ->contain(['Users'])));
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
			, 'Clazzes.Locals', 'Clazzes.Processes', 'Roles', 'Roles.Knowledges'
			, 'KnowledgesTeachers', 'KnowledgesTeachers.Teachers', 'KnowledgesTeachers.Knowledges']
        ]);

        if ($teacher->entry_date) {
            $teacher->entry_date = $teacher->entry_date->i18nFormat('dd/MM/yyyy');
        }
        if ($teacher->birth_date) {
            $teacher->birth_date = $teacher->birth_date->i18nFormat('dd/MM/yyyy');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $i = 0;
            foreach ($this->request->data['knowledgeTeacher']['level'] as $level) {
                if ($level == 1 || $level == 2 || $level == 3) {
                    $teacher->knowledges_teachers[$i]->level = $level;
                }
                $i++;
            }
            $teacher->dirty('knowledges_teachers', true);
            unset($this->request->data['knowledgeTeacher']);

            $teacher = $this->Teachers->patchEntity($teacher, $this->request->data(), [
                'associated' => ['Users' => ['validate' => 'default'], 'KnowledgesTeachers']
            ]);

            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));
            } else {
                $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
            }
        }

        $scheduleLocals = [];

        $currentProcess = TableRegistry::get('Processes')->find('all', [
            'conditions' => ['Processes.initial_date <=' => new \DateTime(), 'Processes.status != ' => 'CANCELLED'],
            'order' => ['Processes.initial_date' => 'DESC']
        ])->first();

        if ($currentProcess) {
            $clazzes = TableRegistry::get('Clazzes')->find()
                    ->innerJoinWith(
                        'Teachers', function ($q) use ($id) {
                            return $q->where(['Teachers.id' => $id]);
                        }
                    )
                    ->innerJoinWith(
                          'Processes', function ($q) use ($currentProcess) {
                              return $q->where(['Processes.id' => $currentProcess->id]);
                          }
                      )
                    ->contain(['ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules'])->all();

            foreach ($clazzes as $clazze) {
                foreach($clazze->scheduleLocals as $scheduleLocal) {
                    $scheduleLocals[] = $scheduleLocal;
                }
            }
        }




        $this->set('teacher', $teacher);
        $this->set('scheduleLocals', $scheduleLocals);
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
        $knowledges = $this->Knowledges->find('all');

		if ($this->request->is('post')) {
			$data = $this->request->data;
			$data['user']['is_admin'] = isset($this->request->data['user']['is_admin']) ? 1 : 0;

            $knowledgesTeachers = [];
            foreach($knowledges as $knowledge) {
                $knowledgesTeachers[] = [
                    "knowledge_id" => $knowledge->id,
                    "level" => 3
                ];
            }
            $data['knowledges_teachers'] = $knowledgesTeachers;

            $teacher = $this->Teachers->patchEntity($teacher, $data, [
                    'associated' => ['KnowledgesTeachers', 'Users' => ['validate' => 'default']]
                ]);
            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));
                return $this->redirect(['action' => 'index']);
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
            'contain' => ['Users'
				, 'Clazzes'
				, 'Clazzes.Subjects'
				, 'Clazzes.Subjects.Knowledges'
				, 'Clazzes.Subjects.Courses'
				, 'KnowledgesTeachers'
				, 'KnowledgesTeachers.Teachers'
				, 'KnowledgesTeachers.Knowledges'
			]
        ]);

        $teacher->entry_date = $teacher->entry_date->i18nFormat('dd/MM/yyyy');
        $teacher->birth_date = $teacher->birth_date->i18nFormat('dd/MM/yyyy');

        if ($this->request->is(['patch', 'post', 'put'])) {
			$data = $this->request->data;
			$data['user']['is_admin'] = isset($this->request->data['user']['is_admin']) ? 1 : 0;

			if (!empty($this->request->data['pwd'])) {
				$data['user']['password'] = $data['pwd'];
				unset($data['pwd']);
			}

            $i = 0;
			foreach ($this->request->data['knowledgeTeacher']['level'] as $level) {
			    if ($level == 1 || $level == 2 || $level == 3) {
                    $teacher->knowledges_teachers[$i]->level = $level;
			    }
			    $i++;
			}

            $teacher->dirty('knowledges_teachers', true);
			$teacher = $this->Teachers->patchEntity($teacher, $data, [
				'associated' => ['Users' => ['validate' => 'default'], 'KnowledgesTeachers']
			]);

            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
            }
        }
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
            ->where(['status' => 'OPENED'])
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
							'teacher_id' => $id,
							'status != ' => 'SELECTED'
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
                'Processes', 'ClazzesTeachers'
			])
			->innerJoinWith('Processes', function ($q) use ($params) {
				return $q->where(['Processes.status' => 'OPENED']);
			});

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
						return $q->where(['Processes.id LIKE ' => '%' . $params['process'] . '%']);
					},
					'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules', 'ClazzesTeachers'
				])
				->innerJoinWith('ClazzesSchedulesLocals.Locals', function ($q) use ($params) {
						return $q->where(['Locals.name LIKE ' => '%' . $params['local'] . '%'])
								->orWhere(['Locals.address LIKE ' => '%' . $params['local'] . '%']);
					})
				->innerJoinWith('ClazzesSchedulesLocals.Schedules', function ($q) use ($params) {
						return $q->where(['Schedules.start_time >= ' => $params['start_time']['hour'] . ':' . $params['start_time']['minute'],
								'Schedules.end_time <= ' => $params['end_time']['hour'] . ':' . $params['end_time']['minute']]);
					})
				->innerJoinWith('Processes', function ($q) use ($params) {
					return $q->where(['Processes.status' => 'OPENED']);
				});

			if (!empty($params['week_day'])) {
				$data->where(['week_day' => $params['week_day']]);
			}


			$data->group(['Clazzes.id']);

        }

		return $data->all();
	}

    public function getSubAllocatedTeachers() {
            $table_processes = TableRegistry::get('Processes');
            $processes = $table_processes->find('all')->where(['status' => 'OPEN']);
            $this->set("processes", $processes->execute()->fetchAll('assoc'));
            $precess_selected = array_key_exists('process',$_GET);
            $this->set("precess_selected", $precess_selected);
            if($precess_selected){
                $process = $table_processes->get((int)$_GET["process"]);
                $this->set("teachers", $this->Teachers->getSubAllocated($process));
            }
        }
}

