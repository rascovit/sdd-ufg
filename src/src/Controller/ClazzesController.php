<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\ORM\Query;

/**
 * Clazzes Controller
 *
 * @property \App\Model\Table\ClazzesTable $Clazzes
 */
class ClazzesController extends AppController
{
	public function isAuthorized($user)
	{
		// Need to be logged
        $loggedActions = ['listOpenedClazzes', 'view', 'index'];
        if (in_array($this->request->action, $loggedActions) && $this->loggedUser !== false) {
            return True;
		}

		// Need to be logged as admin or coordinator/facilitator
        $adminOrCoordinator = ['allocateTeacher'];
        if (in_array($this->request->action, $adminOrCoordinator) && ($this->loggedUser !== false)) {
			$clazzId = (int)$this->request->params['pass'][0];
			$clazz = $this->Clazzes->get($clazzId, [
				'contain' => [
					'Subjects'
				]
			]);
			$knowledgeId = $clazz->subject->knowledge_id;

			if($this->loggedUser->canAdmin() || $this->loggedUser->isFacilitatorOf($knowledgeId)) {
				return True;
			}
			return false;
		}

        // Need to be logged ONLY by a teacher
        if(in_array($this->request->action, ['subscribe', 'unsubscribe'])) {
            if(isset($this->loggedUser->teacher) && $this->loggedUser->teacher != null) {
                return True;
            }

            return False;
        }

		//Only teacher can finish his/her clazz
		if (in_array($this->request->action, ['finishClazze'])) {
			$clazzId = (int)$this->request->params['pass'][0];

			$teacherIds = array();

			$teachers = $this->Clazzes->ClazzesTeachers->find('all',
				['conditions' => ['clazz_id' => $clazzId, 'status' => 'SELECTED']])->toArray();

			foreach($teachers as $t) {
				$teacherIds[] = $t['teacher_id'];
			}

			if (in_array($this->loggedUser->teacher->id, $teacherIds)) {
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
        $this->paginate = [
            'limit' => 25,
            'order' => [
                'Clazzes.id' => 'DESC'
            ]
        ];

        $this->request->data = $this->request->query;
        $clazzes = $this->Clazzes->findByFilters($this->request->query);

        $this->set('isFiltered', !empty($clazzes->__debugInfo()['params']));
        $this->set('status', ['' => __('[Selecione]'), 'OPENED' => __('Aberto'), 'CONFLICT' => _('Em conflito'), 'CLOSED' => __('Fechado')]);
        $this->set('subjects', array_replace([0 => __('[Selecione]')], $this->Clazzes->Subjects->find('list')->toArray()));
        $this->set('processes', array_replace([0 => __('[Selecione]')], $this->Clazzes->Processes->find('list')->toArray()));
        $this->set('knowledges', array_replace([0 => __('[Selecione]')], $this->Clazzes->Subjects->Knowledges->find('list')->toArray()));

        $this->Clazzes->ClazzesTeachers->Teachers->displayField('user.name');
        $this->set('teachers', $this->Clazzes->ClazzesTeachers->Teachers->find('list')->contain(['Users'])->toArray());
        $this->set('schedules', $this->Clazzes->ClazzesSchedulesLocals->find('list')->contain(['Schedules', 'Locals'])->group(['schedule_id', 'local_id'])->toArray());

        $this->set('clazzes', $this->paginate($clazzes));
        $this->set('_serialize', ['clazzes']);
    }

    /**
     * View method
     *
     * @param string|null $id Clazze id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $clazz = $this->Clazzes->get($id, [
            'contain' => [
                'Processes', 'Subjects.Knowledges', 'ClazzesTeachers.Teachers.Users',
                'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules'
            ]
        ]);

        if ($this->request->is('post') && $this->loggedUser->isClazzAdmin($clazz)) {
            $selectedTeachers = isset($this->request->data['selected_teachers']) ?
                $this->request->data['selected_teachers'] : [];

            if(empty($selectedTeachers)) {
                $this->Flash->error(__('Nenhum docente foi selecionado para ser alocado a turma.'));
                return $this->redirect(['action' => 'view', $id]);
            }

            $this->Clazzes->ClazzesTeachers->updateAll([
                'status' => 'REJECTED'
            ], [
                'clazz_id' => $id
            ]);

            $this->Clazzes->ClazzesTeachers->updateAll([
                'status' => 'SELECTED'
            ], [
                'teacher_id IN' => $selectedTeachers,
                'clazz_id' => $id
            ]);

            $this->Flash->success(__('Docentes alocados à turma com sucesso.'));
            $clazz = $this->Clazzes->get($id, [
                'contain' => [
                    'Processes', 'Subjects.Knowledges', 'ClazzesTeachers.Teachers.Users',
                    'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules'
                ]
            ]);
        }

        $this->set('clazz', $clazz);
        $this->set('_serialize', ['clazz']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $clazz = $this->Clazzes->newEntity();
        if ($this->request->is('post')) {
            if(isset($this->request->data['schedules'])) {
                $schedules = json_decode($this->request->data['schedules'], true);
                $schedules = is_array($schedules) ? $schedules : [];
                $scheduleLocals = [];
                foreach($schedules as $weekDay) {
                    if($weekDay == null) {
                        continue;
                    }
                    foreach($weekDay as $schedule) {
                        $scheduleLocals[] = [
                            "schedule_id" => $schedule['schedule_id'],
                            "local_id" => $schedule['local_id'],
                            "week_day" => $schedule['week_day']
                        ];
                    }
                }
                $this->request->data['scheduleLocals'] = $scheduleLocals;
            }
            $data = $this->request->data;
            unset($data['schedules']);
            $clazz = $this->Clazzes->patchEntity($clazz, $data, [
                'associated' => ['ClazzesSchedulesLocals']
            ]);

            if ($this->Clazzes->save($clazz)) {
                $this->Flash->success(__('Turma adicionada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível adicionar a turma, tente novamente.'));
            }
        }

        $this->set('subjects', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Subjects->find('list')->toArray()));
        $this->set('processes', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Processes->find('list')->toArray()));

        $this->set('locals', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Locals->find('list')->toArray()));
        $this->set('schedules', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Schedules->find('list')->toArray()));

        $this->set(compact('clazz'));
        $this->set('_serialize', ['clazz']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Clazze id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $clazz = $this->Clazzes->get($id, [
            'contain' => ['Processes', 'Subjects', 'ClazzesSchedulesLocals']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['schedules'])) {
                $schedules = json_decode($this->request->data['schedules'], true);
                $schedules = is_array($schedules) ? $schedules : [];
                $scheduleLocals = [];
                foreach($schedules as $weekDay) {
                    if($weekDay == null) {
                        continue;
                    }
                    foreach($weekDay as $schedule) {
                        $scheduleLocalEnt = $this->Clazzes->ClazzesSchedulesLocals->newEntity([
                            "clazz_id" => $clazz->id,
                            "schedule_id" => $schedule['schedule_id'],
                            "local_id" => $schedule['local_id'],
                            "week_day" => $schedule['week_day']
                        ]);

                        $scheduleLocals[] = $scheduleLocalEnt;
                    }
                }
            }
            $data = $this->request->data;
            unset($data['schedules']);
            $clazz = $this->Clazzes->patchEntity($clazz, $data);
            if(isset($scheduleLocals)) {
                $this->Clazzes->ClazzesSchedulesLocals->deleteAll(['clazz_id' => $clazz->id]);
                $clazz->scheduleLocals = $scheduleLocals;
                $clazz->dirty('scheduleLocals', true);
            }

            if ($this->Clazzes->save($clazz)) {
                $this->Flash->success(__('Turma modificada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível modificar a turma, tente novamente.'));
            }
        }

        $schedules = [[], [], [], [], [], [], [], []];
        $schedulesIndex = 0;
        foreach($clazz->scheduleLocals as $scheduledLocal) {
            $schedules[$scheduledLocal->week_day][] = [
                "id" => $schedulesIndex++,
                "week_day" => $scheduledLocal->week_day,
                "schedule_id" => $scheduledLocal->schedule_id,
                "local_id" => $scheduledLocal->local_id
            ];
        }

        $this->set('scheduledLocals', json_encode($schedules));

        $this->set('subjects', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Subjects->find('list')->toArray()));
        $this->set('processes', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Processes->find('list')->toArray()));

        $this->set('locals', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Locals->find('list')->toArray()));
        $this->set('schedules', array_replace([0 => __('[Selecione]')],
            $this->Clazzes->Schedules->find('list')->toArray()));

        $this->set(compact('clazz'));
        $this->set('_serialize', ['clazz']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Clazze id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $clazze = $this->Clazzes->get($id);
        if ($this->Clazzes->delete($clazze)) {
            $this->Flash->success(__('Turma removida com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível remover a turma, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Subscribe method
     *
     * @param string|null $id Clazz id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function subscribe($id = null)
    {
        $this->request->allowMethod(['post']);
        if($this->Clazzes->isTeacherSubscribed($this->loggedUser->teacher->id, $id)) {
            $this->Flash->error(__('Você já está inscrito nesta turma.'));
            return $this->redirect(['action' => 'view', $id]);
        }

        $clazzTeacher = $this->Clazzes->ClazzesTeachers->newEntity([
            'teacher_id' => $this->loggedUser->teacher->id,
            'clazz_id' => $id,
            'status' => 'PENDING'
        ]);

        if($this->Clazzes->ClazzesTeachers->save($clazzTeacher)) {
            $this->Flash->success(__('Inscrição realizada com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível realizar sua inscrição, tente novamente.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Unsubscribe method
     *
     * @param string|null $id Clazz id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function unsubscribe($id = null)
    {
        $this->request->allowMethod(['post']);
        if(!$this->Clazzes->isTeacherSubscribed($this->loggedUser->teacher->id, $id)) {
            $this->Flash->error(__('Você não está inscrito nesta turma.'));
            return $this->redirect(['action' => 'view', $id]);
        }

        $deleteConditions = [
            'teacher_id' => $this->loggedUser->teacher->id,
            'clazz_id' => $id
        ];

        if($this->Clazzes->ClazzesTeachers->deleteAll($deleteConditions)) {
            $this->Flash->success(__('Inscrição cancelada com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível cancelar sua inscrição, tente novamente.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /** END Basics */

	/**
	*
	* Show current user/teacher 's intents
	*/
	public function myIntents()
    {
		$clazzes = $this->Clazzes->ClazzesTeachers->getIntentsByTeacher($this->loggedUser->teacher->id);

		$this->set('clazzes', $this->paginate($clazzes));
		$this->set('_serialize', ['clazzes']);
		$this->set('teacherId', $this->loggedUser->teacher->id);
    }


	/**
	*
	* List opened clazzes
	*/
	public function listOpenedClazzes()
	{
		$processes = $this->Clazzes->Processes->find('list')
            ->where(['initial_date <= ' => 'CURDATE()', 'final_date >= ' => 'CURDATE()'])
            ->orWhere(['status' => 'OPENED'])
            ->toArray();

        $processes = array_replace(['' => __('[Selecione]')], $processes);

		if (count($processes) < 2) {
			$this->Flash->info(__('Não existe nenhum Processo de Distribuição de Disciplinas aberto.'));
			$this->set('process_exists', false);
			$this->set('_serialize', ['process_exists']);
			$this->set('clazzes', array());
			$this->set('_serialize', ['clazzes']);
			$this->set('processes', array());
			$this->set('_serialize', ['processes']);

		} else {
			$clazzes = $this->getOpenedClazzes();

			/* Filters */
			if ($this->request->is('post')) {
				$data = $this->request->data;
				$clazzes = $this->getOpenedClazzes($data);
				echo json_encode($clazzes);
				die();
			}

			$this->set('clazzes', $clazzes);
			$this->set('_serialize', ['clazzes']);
			$this->set('processes', $processes);
			$this->set('_serialize', ['processes']);
			$this->set('process_exists', true);
			$this->set('_serialize', ['process_exists']);
		}
	}


	private function getOpenedClazzes($params = null)
	{

		$data = $this->Clazzes->find('all')
					->contain([
						'Subjects.Courses', 'Subjects.Knowledges',
						'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules',
						'Processes',
					]);

        if($params !== null) {

			$data = $this->Clazzes->find('all')
					->contain([
						'Subjects.Courses', 'Subjects.Knowledges' => function ($q) use ($params) {
							return $q->where(["Knowledges.name LIKE " => "%" . $params['knowledge_name'] . "%"]);
						},
						'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules',
						'Processes' => function ($q) use ($params) {
							return $q->where(["Clazzes.process_id LIKE " => "%" . $params['process'] . "%"]);
						}
					]);

        }

		$roles = array();
		foreach ($this->loggedUser->teacher->roles as $r) {
			$roles[] = $r->type;
		}

		if (!in_array('COORDINATOR', $roles) && in_array('FACILITATOR', $roles)) {
			$data->innerJoinWith('Subjects.Knowledges', function($q) {
				return $q->where(['Knowledges.id IN ' => $roles]);
			});
		}

		foreach($data as $clazz => $value) {
			if ($value->_getStatus() == 'CLOSED') {
				unset($data[$clazz]);
			}
		}

        return $data->toArray();
	}

	/**
	* Allocate Teacher method
	*
	*/
	public function allocateTeacher($clazz_id, $teacher_id = null, $allocate = false)
	{

		$table_clazzes_teachers = TableRegistry::get('ClazzesTeachers');

		$clazzesTeachers = $table_clazzes_teachers->find('all')->where(['clazz_id' => $clazz_id]);

		$clazz = $this->Clazzes->get($clazz_id, [
            'contain' => [
				'Subjects'
				, 'Subjects.Knowledges'
				, 'Subjects.Courses'
				, 'ClazzesSchedulesLocals'
				, 'ClazzesTeachers'
			]
        ]);

		$teachers = $this->getTeachers();

		$recomendedTeacher = null;
		$maior = 0;
		foreach ($clazzesTeachers->all() as $c) {
			if ($maior < $c->priority) {
				$maior = $c->priority;
				$recomendedTeacher = $c->teacher_id;
			}
		}

		if ($clazz_id != null && $teacher_id != null) {

			if ($this->RequestHandler->accepts('ajax')) {

				$this->response->disableCache();

				if ($teacher_id != null && $clazz_id != null && $allocate == 'allocate') {

					$query = $table_clazzes_teachers->query();
					$query->update()
							->set(['status' => 'REJECTED'])
							->where([
							'clazz_id' => $clazz_id,
							'teacher_id != ' => $teacher_id,
							'status' => 'PENDING'
					])->execute();

					$query = $table_clazzes_teachers->query();
					$query->delete()->where([
							'clazz_id' => $clazz_id,
							'teacher_id' => $teacher_id
					])->execute();

					$query = $table_clazzes_teachers->query();
					$query->insert(['clazz_id', 'teacher_id', 'status'])->values([
							'clazz_id' => $clazz_id,
							'teacher_id' => $teacher_id,
							'status' => 'SELECTED'
						])->execute();

					if ($query) {
						echo 'success';
					} else {
						echo 'error';
					}

					die();
				} else if ($teacher_id != null && $clazz_id != null && $allocate == 'deallocate') {

					if (count($clazz->selectedTeachersIds) >= 1) {
						$query = $table_clazzes_teachers->query();
						$query->update()
								->set(['status' => 'REJECTED'])
								->where([
								'clazz_id' => $clazz_id,
								'teacher_id' => $teacher_id
						])->execute();
						
						$query->update()
								->set(['status' => 'REJECTED'])
								->where([
								'clazz_id' => $clazz_id,
								'status' => 'PENDING'
						])->execute();
					} else {
						$query = $table_clazzes_teachers->query();
						$query->update()
								->set(['status' => 'PENDING'])
								->where([
								'clazz_id' => $clazz_id,
								'status != ' => 'SELECTED'
						])->execute();
					}

					if ($query) {
						echo 'success';
					} else {
						echo 'error';
					}
					die();

					if ($query) {
						echo 'success';
					} else {
						echo 'error';
					}
					die();
				}
			}
		}

		/* Filters */
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$teachers = $this->getTeachers($data);
			echo json_encode($teachers);
			die();
		}

		$this->set(compact('clazz'));
		$this->set('_serialize', ['clazz']);
		$this->set('teachers', $teachers);
		$this->set('_serialize', ['teachers']);
		$this->set('clazzesTeachers', $clazzesTeachers);
		$this->set('_serialize', ['clazzesTeachers']);
		$this->set('recomendedTeacher', $recomendedTeacher);
	}

	private function getTeachers($params = null) {

		$this->loadModel('Teachers');

		if ($params == null) {
			return $this->paginate(
				$this->Teachers->find('all')->contain(['Users', 'Clazzes', 'Clazzes.Subjects'
				, 'Clazzes.Processes', 'Knowledges', 'Roles', 'Roles.Knowledges'])
			);

		} else {

			$clazz = $this->Clazzes->get($params['clazz_id']);

			$query = $this->Teachers->find('all')
						->distinct(['Teachers.id'])
						->where([
								'COALESCE(Teachers.registry, "") LIKE ' => '%' . $params['registry'] . '%',
								'COALESCE(Teachers.workload, "") LIKE ' => '%' . $params['workload'] . '%',
								'COALESCE(Teachers.formation, "") LIKE ' => '%' . $params['formation'] . '%',
								'COALESCE(Teachers.situation, "") LIKE ' => '%' . $params['situation'] . '%'
								])
						->contain(['Users', 'Knowledges',
							'Clazzes', 'Clazzes.Subjects',
							'Clazzes.Processes', 'Roles', 'Roles.Knowledges'])
						->matching('Users', function($q) use ($params) {
							return $q->where(['Users.name LIKE ' => '%' . $params['name'] . '%']);
						});



			if ($params['only_clazz'] == 1) {
				$query->matching('ClazzesTeachers', function($q) use ($clazz) {
					return $q->where(['ClazzesTeachers.clazz_id' => $clazz->id]);
				});
			}

			if ($params['knowledge'] == '') {

				if ($params['only_knowledge'] == 0) {
					$query->leftJoinWith('Knowledges', function($q) use ($params) {
						return $q->where(['COALESCE(Knowledges.name, "") LIKE ' => '%' . $params['knowledge'] . '%']);
					});
				} else {
					$params_clazz = array();
					$params_clazz[] = $params;
					$params_clazz[] = $clazz;

					$query->leftJoinWith('Knowledges', function($q) use ($params_clazz) {
						return $q->where(['COALESCE(Knowledges.name, "") LIKE ' => '%' . $params_clazz[0]['knowledge'] . '%'])
							->where(['Knowledges.id' => $params_clazz[1]->knowledge]);
					});
				}


			} else {
				if ($params['only_knowledge'] == 0) {
					$query->matching('Knowledges', function($q) use ($params) {
						return $q->where(['COALESCE(Knowledges.name, "") LIKE ' => '%' . $params['knowledge'] . '%']);
					});
				} else {

					$query->matching('Knowledges', function($q) use ($params) {
						return $q->where(['COALESCE(Knowledges.name, "") LIKE ' => '%' . $params['knowledge'] . '%'])
							->where(['Knowledges.id' => $clazz->knowledge]);
					});
				}
			}

			return $query->all();

		}
	}

	/**
     * Finish the selected clazze
     *
     * @param string|null $id Clazze id.
     * @return void
     */
	public function finishClazze($id = null)
	{
        $clazze = $this->Clazzes->get($id, [
            'contain' => []
        ]);

		$clazzes_dir = $this->Clazzes->checkDirectory(WWW_ROOT.'/finishedClazzes');
		$dir = $this->Clazzes->checkDirectory(WWW_ROOT.'/finishedClazzes/clazz-' . $id);

		$files = $dir->find();

		if ($this->request->is('post')) {
			foreach ($files as $file) {
				$file = new File($dir->pwd() . DS . $file);
				$file->delete();
				$file->close();
			}

			$data = $this->request->data;
			$invalidNames = false;
			$invalidExt = false;

			foreach ($data as $file) {
				if (!$this->Clazzes->checkName($file['name'])) {
					$this->Flash->error(__('Um ou mais nomes de arquivos são inválidos. Verifique e tente novamente. ' .
							'(Nome inválido: ' . $file['name'] .  ')'));
					$invalidNames = true;
					break;
				}
			}
			
			foreach ($data as $file) {
				if ($file['type'] != 'application/pdf') {
					$this->Flash->error(__('Um ou mais arquivos têm extensão inválida (diferente de .pdf). Verifique e tente novamente. ' .
							'(Arquivo inválido: ' . $file['name'] .  ')'));
					$invalidExt = true;
					break;
				}
			}

			$error = false;

			if (!$invalidNames && !$invalidExt) {
				foreach ($data as $file) {

					if (!move_uploaded_file($file['tmp_name'], $dir->pwd() . DS . $file['name'])) {
						$this->Flash->error(__('Ocorreu um erro ao fazer o upload de um ou mais arquivos. Tente novamente.'));
						$error = true;
						break;
					}
				}
			}

			if (!$invalidNames && !$invalidExt && !$error) {
				$this->Flash->success(__('Arquivos de Finalização de Turma salvos com sucesso!'));
				return $this->redirect(['action' => 'index']);
			}

		}

		$this->set('files', $files);
        $this->set('clazze', $clazze);
        $this->set('_serialize', ['clazze']);
	}

}
