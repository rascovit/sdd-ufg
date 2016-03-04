<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use App\Lib\Distribution\PriorityIndex;
use App\Lib\Distribution\Distribution;
use Cake\Event\Event;

/**
 * Processes Controller
 *
 * @property \App\Model\Table\ProcessesTable $Processes
 */

/**
 * TODO
 * - Criar tela para seleção de processo antes da distribuição automática (OK)
 * - Incrementa currentWorkload (OK)
 * - Reverter processo
 * - Mostrar alocadas dinamicamente (OK)
 * - Testar até deadline do projeto
 */

class ProcessesController extends AppController
{

    public function isAuthorized($user)
    {
        // Need to be logged
        $loggedActions = ['index', 'view'];
        if (in_array($this->request->action, $loggedActions) && $this->loggedUser !== false) {
            return true;
        }

        //Only allowed to clone closed process
        if (in_array($this->request->action, ['reuseProcess'])) {
            $processId = (int)$this->request->params['pass'][0];

            $process = $this->Processes->get($processId);

            if ($this->loggedUser !== false && $this->loggedUser->canAdmin()
                && $process->status == 'CLOSED'
            ) {
                return true;
            }

            return false;
        }

        return parent::isAuthorized($user);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['prototypeDistribute']);

        $redirectToHome = ['requestAccount', 'login'];
        $authUser = isset($this->request->Session()->read('Auth')['User']) ? true : false;
        if ($authUser && in_array($this->request->params['action'], $redirectToHome)) {
            return $this->redirect('/');
        }
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
                'Processes.id' => 'ASC'
            ]
        ];

        $this->request->data = $this->request->query;
        $this->Processes = $this->Processes->findByFilters($this->request->query);

        $this->set('processes', $this->paginate($this->Processes));
        $this->set('_serialize', ['processes']);
    }

    /**
     * View method
     *
     * @param string|null $id Process id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $process = $this->Processes->get($id, [
            'contain' => [
                'ProcessConfigurations', 'Clazzes'
            ]
        ]);
        $this->set('process', $process);
        $this->set('_serialize', ['process']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $process = $this->Processes->newEntity();
        if ($this->request->is('post')) {
            $process = $this->Processes->patchEntity($process, $this->request->data);
            $process->status = 'OPENED';
            if ($this->Processes->save($process)) {
                $this->Flash->success(__('O processo foi salvo.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível salvar o processo. Tente novamente.'));
            }
        }
        $this->set(compact('process'));
        $this->set('_serialize', ['process']);
        $this->set('processConfigurations', $this->Processes->ProcessConfigurations->find('list'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Process id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $process = $this->Processes->get($id, [
            'contain' => ['ProcessConfigurations', 'Clazzes']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $process = $this->Processes->patchEntity($process, $this->request->data);
            if ($this->Processes->save($process)) {
                $this->Flash->success(__('O processo foi salvo!'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível salvar o processo. Tente novamente.'));
            }
        }
        $this->set(compact('process'));
        $this->set('_serialize', ['process']);
        $this->set('processConfigurations', $this->Processes->ProcessConfigurations->find('list'));
    }

    /**
     * Cancel method
     *
     * @param string|null $id Process id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function cancel($id = null)
    {
        $this->request->allowMethod(['post', 'cancel']);
        $process = $this->Processes->get($id);
        $process->status = 'CANCELLED';
        if ($this->Processes->save($process)) {
            $this->Flash->success(__('O processo foi cancelado com sucesso.'));
        } else {
            $this->Flash->error(__('O processo nao pode ser cancelado. Por favor, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Close method
     *
     * @param string|null $id Process id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function close($id = null)
    {
        $this->request->allowMethod(['post', 'close']);
        $process = $this->Processes->get($id);
        $process->status = 'CLOSED';
//        $process->dirty('processConfigurations');
        if ($this->Processes->save($process)) {
            $this->Flash->success(__('O processo foi fechado com sucesso.'));
        } else {
            $this->Flash->error(__('O processo nao pode ser fechado. Por favor, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /*
        Soma o prática e teórica da disciplina da turma e divide por 16
        E vê se o valor da ao menos oq ta no workload do docente que e na maioria 8
    */

    public function prototypeDistribute()
    {
        //http://localhost:8765/processes/prototype-distribute
        $this->autoRender = false;
        $this->response->type('json');
        $clazzes = $this->Processes->Clazzes->getAllClazzesWithSubjctsTeachers();
        $teachers = TableRegistry::get('Teachers')->getAllTeachersWithKnowledge()->toArray();
        $clazzes = Distribution::generateDistribution($clazzes, $teachers);
        $this->Processes->Clazzes->setTeachersAllClazzes($clazzes);
        $clazzes = $this->Processes->Clazzes->getAllClazzesWithSubjctsTeachers();
        $this->response->body(json_encode($clazzes, JSON_PRETTY_PRINT));
    }

    public function distribute($processId = null)
    {
        $clazzes = $this->Processes->Clazzes->find('all')->where(['process_id' => $processId])->contain(['ClazzesTeachers.Teachers.Users', 'Locals', 'Subjects']);
        $clazzes = $this->paginate($clazzes);
        $this->set('clazzes', $clazzes);

        $teachers = $this->Processes->Clazzes->ClazzesTeachers->Teachers->find('all')->contain(['Users', 'KnowledgesTeachers']);
        $teachers = $this->paginate($teachers);
        $this->set('teachers', $teachers);

        // PEGA AS TURMAS JÁ ALOCADAS E NÃO CONFLITANTES
        $allocatedAndNonConflictingClazzes = $this->getAllocatedAndNonConflictingClazzes($clazzes);
        $this->set('allocatedAndNonConflictingClazzes', $allocatedAndNonConflictingClazzes);

        // PEGA AS TURMAS NAO ALOCADAS OU CONFLITANTES
        $conflictedAndUnallocatedClazzes = $this->getUnallocatedAndConflictingClazzes($clazzes);
        $this->set('conflictedAndUnallocatedClazzes', $conflictedAndUnallocatedClazzes);

        // PEGAR CARGA HORÁRIA ATUAL
        $teachersCurrentWorkload = $this->getTeachersCurrentWorkload($teachers, $clazzes);
        $this->set('teachersCurrentWorkload', $teachersCurrentWorkload);

        // COMPARA CARGA HORÁRIA ATUAL COM A CARGA HORÁRIA PREVISTA
        $subAndSuperAllocatedTeachers = $this->getSubAndSuperAllocatedTeachers($teachers, $clazzes);
        $this->set('subAndSuperAllocatedTeachers', $subAndSuperAllocatedTeachers);

        $this->set('processId', $processId);
    }

    private function getAllocatedAndNonConflictingClazzes($clazzes) {
        $allocatedAndNonConflictingClazzes = [];

        foreach ($clazzes as $clazz) {
            foreach ($clazz->intents as $intent) {
                if ($intent->status == 'SELECTED' && $intent->simulation_id == null) {
                    $allocatedAndNonConflictingClazzes[$clazz->id] = ['subjectName' => $clazz->subject->name,
                        'teacherRegistry' => $intent->teacher->registry, 'userName' => $intent->teacher->user->name];
                    foreach ($clazz->locals as $local) {
                        $allocatedAndNonConflictingClazzes[$clazz->id]['locals'] = $local->name . ' [' . $local->address . ']';
                        $allocatedAndNonConflictingClazzes[$clazz->id]['schedules'] = $local->_joinData->schedule_id;
                    }

                }
            }
        }

        return $allocatedAndNonConflictingClazzes;
    }

    private function getUnallocatedAndConflictingClazzes($clazzes) {
        $conflictedAndUnallocatedClazzes = [];

        foreach ($clazzes as $clazz) {
            if (empty($clazz->intents)) {
                $conflictedAndUnallocatedClazzes[$clazz->id]['clazzeName'] = $clazz->name;
                $conflictedAndUnallocatedClazzes[$clazz->id]['subjectId'] = $clazz->subject->id;
                $conflictedAndUnallocatedClazzes[$clazz->id]['subjectName'] = $clazz->subject->name;
                $conflictedAndUnallocatedClazzes[$clazz->id]['knowledgeId'] = $clazz->subject->knowledge_id;
                $conflictedAndUnallocatedClazzes[$clazz->id]['totalSubjectWorkload'] = ($clazz->subject->theoretical_workload + $clazz->subject->practical_workload) / 16;
                $conflictedAndUnallocatedClazzes[$clazz->id]['intents'] = $clazz->intents;

            }
            foreach ($clazz->intents as $intent) {
                if ($intent->status == 'PENDING') {
                    $conflictedAndUnallocatedClazzes[$clazz->id]['clazzeName'] = $clazz->name;
                    $conflictedAndUnallocatedClazzes[$clazz->id]['subjectId'] = $clazz->subject->id;
                    $conflictedAndUnallocatedClazzes[$clazz->id]['subjectName'] = $clazz->subject->name;
                    $conflictedAndUnallocatedClazzes[$clazz->id]['knowledgeId'] = $clazz->subject->knowledge_id;
                    $conflictedAndUnallocatedClazzes[$clazz->id]['totalSubjectWorkload'] = ($clazz->subject->theoretical_workload + $clazz->subject->practical_workload) / 16;
                    $conflictedAndUnallocatedClazzes[$clazz->id]['intents'] = $clazz->intents;

                }
            }
        }

        return $conflictedAndUnallocatedClazzes;
    }

    private function getTeachersCurrentWorkload($teachers, $clazzes) {
        // Hashmap com key=idProfessor e value=cargaHoráriaAtual
        $teachersCurrentWorkload = [];

        // Inicializando o hashmap de carga horária
        foreach ($teachers as $teacher) {
            $teachersCurrentWorkload[$teacher->id] = 0;
        }

        foreach ($teachers as $teacher) {
            foreach ($clazzes as $clazz) {
                foreach ($clazz->intents as $intent) {
                    // Se o id do professor estiver em alguns dos intents da turma, soma o sumTheoreticalPratical no hashmap do professor
                    if ($teacher->id == $intent->teacher_id) {

                        // Pega a soma da carga prática e teórica da disciplina
                        $sumTheoreticalPratical = ($clazz->subject->theoretical_workload + $clazz->subject->practical_workload) / 16;

                        // Pega a quantidade atual de horas que o professor já dá de aulas
                        $currentWorkload = $teachersCurrentWorkload[$teacher->id];

                        // Soma a quantidade de horas desta nova disciplina à quantidade atual já ministrada pelo professor
                        $teachersCurrentWorkload[$teacher->id] = ($currentWorkload + $sumTheoreticalPratical);
                    }
                }
            }
        }

        return $teachersCurrentWorkload;
    }

    private function getSubAndSuperAllocatedTeachers($teachers, $clazzes) {
        $subAndSuperAllocatedTeachers = [];
        $teachersCurrentWorkload = $this->getTeachersCurrentWorkload($teachers, $clazzes);

        foreach ($teachers as $teacher) {
            if ($teachersCurrentWorkload[$teacher->id] > $teacher->workload || $teachersCurrentWorkload[$teacher->id] < $teacher->workload) {
                if ($teachersCurrentWorkload[$teacher->id] > $teacher->workload) {
                    $subAndSuperAllocatedTeachers[$teacher->id]['status'] = 'SUPERALOCADO';
                } else if ($teachersCurrentWorkload[$teacher->id] < $teacher->workload) {
                    $subAndSuperAllocatedTeachers[$teacher->id]['status'] = 'SUBALOCADO';
                } else {
                    $subAndSuperAllocatedTeachers[$teacher->id]['status'] = 'NA RISCA';
                }
                $subAndSuperAllocatedTeachers[$teacher->id]['registry'] = $teacher->registry;
                $subAndSuperAllocatedTeachers[$teacher->id]['userName'] = $teacher->user->name;
                foreach ($teacher->knowledges_teachers as $knowledges) {
                    /* Chave = knowledge_id e Valor = level */
                    $subAndSuperAllocatedTeachers[$teacher->id]['knowledges'][$knowledges->knowledge_id] = $knowledges->level;
                }
                $subAndSuperAllocatedTeachers[$teacher->id]['entryDate'] = $teacher->entry_date;
            }
        }

        return $subAndSuperAllocatedTeachers;
    }

    public function simulate($processId = null)
    {
        $clazzes = $this->Processes->Clazzes->find('all')->where(['process_id' => $processId])->contain(['ClazzesTeachers.Teachers.Users', 'Locals', 'Subjects']);
        $clazzes = $this->paginate($clazzes);

        $teachers = $this->Processes->Clazzes->ClazzesTeachers->Teachers->find('all')->contain(['Users', 'KnowledgesTeachers']);
        $teachers = $this->paginate($teachers);

        // NÃO SOFRERAM A DISTRIBUIÇÃO AUTOMÁTICA
        $allocatedAndNonConflictingClazzes = $this->getAllocatedAndNonConflictingClazzes($clazzes);
        $this->set('allocatedAndNonConflictingClazzes', $allocatedAndNonConflictingClazzes);

        $conflictedAndUnallocatedClazzes = $this->getUnallocatedAndConflictingClazzes($clazzes);
        $teachersCurrentWorkload = $this->getTeachersCurrentWorkload($teachers, $clazzes);
        $subAndSuperAllocatedTeachers = $this->getSubAndSuperAllocatedTeachers($teachers, $clazzes);

        // Hashmap com key=ClazzId e Value=array de teacherId com os possíveis cadidatos para aquela turma
        $candidateTeachers = [];

        foreach ($conflictedAndUnallocatedClazzes as $clazzeId => $clazzeInfo) {

            // PASSO 1 - TRATANDO AS TURMAS QUE AINDA NÃO POSSUEM NENHUM INTENT
            if (empty($clazzeInfo['intents'])) {
                $aux = array();

                foreach ($subAndSuperAllocatedTeachers as $teacherId => $teacherInfo) {
                    foreach ($teacherInfo['knowledges'] as $knowledgeId => $knowledgeLevel) {
                        // Se o núcleo de conhecimento da turma for igual ao núcleo de conhecimento do professor e o mesmo não estiver superalocado, ele pode ministrar
                        if ( ($clazzeInfo['knowledgeId'] == $knowledgeId) && ($knowledgeLevel != 3) && ($teacherInfo['status'] != 'SUPERALOCADO') ){

                            array_push($aux, $teacherId);

                        }
                    }
                }

                // Setando o mapa $candidateTeachers
                $candidateTeachers[$clazzeId] = $aux;
            }
            else // PASSO 2 - TRATANDO AS TURMAS COM UMA OU MAIS DE UMA INTENT
            {
                $aux = array();

                foreach ($clazzeInfo['intents'] as $intent) {
                    array_push($aux, $intent->teacher_id);
                }

                // Setando o mapa $candidateTeachers
                $candidateTeachers[$clazzeId] = $aux;
            }
        }

        $priority = [];
        $selectedTeacherId = [];
        $recoveredClazzes = [];

        // DEFININDO A PRIORIDADE ENTRE OS CANDIDATOS E TRATANDO QUANDO NÃO HÁ NENHUM CANDIDATO
        foreach ($candidateTeachers as $clazzeId => $teacherInfo){
            if (empty($teacherInfo)) {
                // --------- SE NÃO TIVER NENHUM DOCENTE APTO PRA DAR AQUELA TURMA, ALOCA O COM MENOR CURRENT WORKLOAD
                $auxMinWorkload = 9999;
                $auxMinWorkloadTeacherId = -1;
                foreach ($teachersCurrentWorkload as $teacherId => $teacherCurrentWorkload) {
                    if ($teachersCurrentWorkload[$teacherId] < $auxMinWorkload) {
                        $auxMinWorkload = $teachersCurrentWorkload[$teacherId];
                        $auxMinWorkloadTeacherId = $teacherId;
                    }
                }
                $selectedTeacherId[$clazzeId] = $auxMinWorkloadTeacherId;
            } else if (count($teacherInfo) == 1) {
                // --------- SE TEM SOMENTE UM DOCENTE APTO PRA DAR AQUELA TURMA, ALOCA ELE PRÓPRIO
                $priority[$clazzeId] = $teacherInfo[0];
                $selectedTeacherId[$clazzeId] = $teacherInfo[0];
            } else {
                // --------- SE TEM MAIS DE UM DOCENTE APTO PRA DAR AQUELA TURMA, CALCULA A PRIORIDADE ENTRE AQUELES DOCENTES
                $selectedTeacherId[$clazzeId] = $this->calculateTeacherPriorityForTheClazz($priority, $clazzeId, $teacherInfo, $teachersCurrentWorkload, $conflictedAndUnallocatedClazzes, $subAndSuperAllocatedTeachers);
            }

            // PERSISTE COMO ACEITO PARA A TURMA
            $this->allocateTeacherForTheClazz($clazzeId, $selectedTeacherId[$clazzeId], $recoveredClazzes);
            // INCREMENTA O CURRENT WORKLOAD DO DOCENTE ACEITO
            $teachersCurrentWorkload[$selectedTeacherId[$clazzeId]] += $conflictedAndUnallocatedClazzes[$clazzeId]['totalSubjectWorkload'];
        }

        $distributedClazzes = $this->getDistributedClazzes($processId);

        // Debug
        $this->set('recoveredClazzes', $recoveredClazzes);
        $this->set('selectedTeacherId', $selectedTeacherId);
        $this->set('candidateTeachers', $candidateTeachers);
        $this->set('priority', $priority);
        $this->set('teachersCurrentWorkload', $teachersCurrentWorkload);
        $this->set('distributedClazzes', $distributedClazzes);
        $this->set('processId', $processId);
    }

    private function calculateTeacherPriorityForTheClazz(&$priority, $clazzeId, $teacherInfo, $teachersCurrentWorkload, $conflictedAndUnallocatedClazzes, $subAndSuperAllocatedTeachers) {

        // Professor com maior ponto, é alocado pra turma
        // Key = teacherId e Value=Priority for the clazz
        $priority[$clazzeId] = [];

        // INICIALIZANDO O VETOR DE PRIORIDADES
        foreach ($teacherInfo as $teacherId) {
            $priority[$clazzeId][$teacherId] = 0;
        }

        // Valores de prioridade:
        //      MinimumWorkload = 1 ponto
        //      BestLevel = 1 ponto
        //      OldestEntryDate = 1 ponto

        // RECEBE O ID DO PROFESSOR
        $minWorkloadTeacherId = null;
        $minWorkload = 9999;

        $bestLevelTeacherId = null;
        $bestLevel = 4;

        $oldestEntryDateTeacherId = null;
        $oldestEntryDate = new Time('now', 'UTC');

        $knowledgeId = null;

        foreach ($teacherInfo as $teacherId) {
            if ($teachersCurrentWorkload[$teacherId] < $minWorkload) {
                $minWorkload = $teachersCurrentWorkload[$teacherId];
                $minWorkloadTeacherId = $teacherId;
            }

            $knowledgeId = $conflictedAndUnallocatedClazzes[$clazzeId]['knowledgeId'];
            if ($subAndSuperAllocatedTeachers[$teacherId]['knowledges'][$knowledgeId] < $bestLevel) {
                $bestLevel = $subAndSuperAllocatedTeachers[$teacherId]['knowledges'][$knowledgeId];
                $bestLevelTeacherId = $teacherId;
            }

            if ($subAndSuperAllocatedTeachers[$teacherId]['entryDate'] < $oldestEntryDate) {
                $oldestEntryDate = $subAndSuperAllocatedTeachers[$teacherId]['entryDate'];
                $oldestEntryDateTeacherId = $teacherId;
            }
        }

        // Vasculhando hashmap de prioridades e selecionando o professor a ser retornado
        $priority[$clazzeId][$minWorkloadTeacherId] += 1;
        $priority[$clazzeId][$bestLevelTeacherId] += 1;
        $priority[$clazzeId][$oldestEntryDateTeacherId] += 1;

        $selectedTeacherId = -1;
        $maximumPoints = -1;

        foreach ($priority[$clazzeId] as $teacherId => $teacherPoints) {
            if ($teacherPoints > $maximumPoints) {
                $selectedTeacherId = $teacherId;
                $maximumPoints = $teacherPoints;
            }
        }

        // SE CADA PONTO FOR ATRIBUIDO A UM DOCENTE DIFERENTE (EMPATE) - PEGA O COM MENOR CARGA HORÁRIA
        $count = 0;
        foreach ($priority[$clazzeId] as $teacherId => $teacherPoints) {
            if ($priority[$clazzeId][$teacherId] == 1) {
                $count++;
            }
        }

        if($count == count($priority[$clazzeId])){
            $selectedTeacherId = $minWorkloadTeacherId;
        }

        $this->set('workload', $minWorkloadTeacherId);
        $this->set('level', $bestLevelTeacherId);
        $this->set('oldestEntryDate', $oldestEntryDateTeacherId);

        return $selectedTeacherId;
    }

    private function allocateTeacherForTheClazz ($clazzeId, $teacherId, &$recoveredClazzes) {

        if ($clazzeId != null && $teacherId != null) {

            // ------------------ PEGANDO AS TABELAS NECESSÁRIAS
            $table_clazzes_teachers = TableRegistry::get('ClazzesTeachers');

            // ------------------ ENCONTRANDO A CLAZZE
            $clazz = $this->Processes->Clazzes->get($clazzeId, [
                'contain' => [
                    'Subjects'
                    , 'Subjects.Knowledges'
                    , 'Subjects.Courses'
                    , 'ClazzesSchedulesLocals'
                    , 'ClazzesTeachers.Teachers.Users'
                ]
            ]);

            // ------------------ VERIFICANDO QUANTOS INTENTS TEM A CLAZZ
            // usado somente para debug
            $recoveredClazzes[$clazzeId] = $clazz;


            // ------------------ EXECUTANDO AS QUERIES PARA CADA UM DOS CASOS

            // CASO 1: SE NÃO HOUVER NENHUM INTENT PRA CLASSE - CRIA UM NOVO INTENT E PERSISTE O PROFESSOR ESCOLHIDO
            if (count($clazz->intents) == 0) {
                $query = $table_clazzes_teachers->query();
                $query->insert(['clazz_id', 'teacher_id', 'status', 'simulation_id', 'previous_status'])->values([
                    'clazz_id' => $clazzeId,
                    'teacher_id' => $teacherId,
                    'status' => 'SELECTED',
                    'simulation_id' => $clazz->process_id,
                    'previous_status' => 'NONE'
                ])->execute();
            }
            // CASO 2: SE HOUVER SOMENTE UM INTENT PRA CLASSE - SIMPLESMENTE TROCA ELE DE STATUS PENDING PRA SELECTED
            else if (count($clazz->intents) == 1) {
                $query = $table_clazzes_teachers->query();
                $query->update()
                    ->set(['status' => 'SELECTED', 'simulation_id' => $clazz->process_id, 'previous_status' => 'PENDING'])
                    ->where([
                        'clazz_id' => $clazzeId,
                        'teacher_id' => $teacherId,
                        'status' => 'PENDING'
                    ])->execute();
            }
            // CASO 3: SE HOUVER MAIS DE UMA INTENT PRA CLASSE - COLOCA UMA DAS INTENTS COMO SELECTED E TODAS AS OUTRAS PASSA PRA REJECTED
            else {
                $query = $table_clazzes_teachers->query();
                $query->update()
                    ->set(['status' => 'REJECTED', 'simulation_id' => $clazz->process_id, 'previous_status' => 'PENDING'])
                    ->where([
                        'clazz_id' => $clazzeId,
                        'teacher_id != ' => $teacherId,
                        'status' => 'PENDING'
                    ])->execute();

                $query = $table_clazzes_teachers->query();
                $query->update()
                    ->set(['status' => 'SELECTED', 'simulation_id' => $clazz->process_id, 'previous_status' => 'PENDING'])
                    ->where([
                        'clazz_id' => $clazzeId,
                        'teacher_id' => $teacherId,
                        'status' => 'PENDING'
                    ])->execute();

            }
        }
    }

    private function getDistributedClazzes($processId)
    {
        $distributedClazzesIntents = $this->Processes->Clazzes->ClazzesTeachers->find('all')->where(['simulation_id' => $processId])->contain(['Teachers.Users', 'Clazzes.Locals', 'Clazzes.Subjects']);
        $distributedClazzesIntents = $this->paginate($distributedClazzesIntents);

        foreach ($distributedClazzesIntents as $clazzIntent) {
            $clazzIntent['clazzeId'] = $clazzIntent->clazz_id;
            $clazzIntent['subjectName'] = $clazzIntent->clazze->subject->name;
            $clazzIntent['teacherRegistry'] = $clazzIntent->teacher->registry;
            $clazzIntent['userName'] = $clazzIntent->teacher->user->name;
            $clazzIntent['status'] = $clazzIntent->status;
            foreach ($clazzIntent->clazze->locals as $local) {
                $clazzIntent['locals'] = $local->name . ' [' . $local->address . ']';
                $clazzIntent['schedules'] = $local->_joinData->schedule_id;
            }
        }

        return $distributedClazzesIntents;
    }

    public function preDistribute()
    {
        $openProcesses = $this->Processes->find('all')->where(['status' => 'OPENED']);
        $this->set('openProcesses', $this->paginate($openProcesses));
        $this->set('_serialize', ['openProcesses']);
    }

    public function preRevert()
    {
        $openProcesses = $this->Processes->find('all')->where(['status' => 'OPENED']);
        $this->set('openProcesses', $this->paginate($openProcesses));
        $this->set('_serialize', ['openProcesses']);
    }

    public function revert($processId = null)
    {
        $distributedClazzes = $this->getDistributedClazzes($processId);
        $this->set('distributedClazzes', $distributedClazzes);
        $this->set('processId', $processId);
    }

    public function effectivateRevert($processId = null) {

        $table_clazzes_teachers = TableRegistry::get('ClazzesTeachers');
        $intents = $table_clazzes_teachers->find('all');

        foreach ($intents as $intent) {
            if($intent->previous_status == 'NONE') {
                $query = $table_clazzes_teachers->query();
                $query->delete()->where(['simulation_id' => $processId, 'previous_status' => 'NONE'])->execute();
            } else {
                $query = $table_clazzes_teachers->query();
                $query->update()
                    ->set(['status' => $intent->previous_status, 'simulation_id' => null, 'previous_status' => 'NONE'])
                    ->where([
                        'simulation_id' => $processId
                    ])->execute();
            }
        }

        $this->Flash->success(__('A distribuição automática foi revertida com sucesso!'));
        $this->redirect( ['controller' => 'processes', 'action' => 'index'] );

    }

    public function effectivateDistribution() {

        $this->Flash->success(__('A distribuição automática foi realizada com sucesso!'));
        $this->redirect( ['controller' => 'processes', 'action' => 'index'] );

    }


    public function cloneProcess($id = null)
    {
        $clonedProcess = $this->Processes->get($id, [
            'contain' => ['Clazzes']
        ]);

        if ($this->request->is('post')) {
            $process = $this->Processes->patchEntity($process, $this->request->data);
            $process->status = 'OPENED';
            if ($this->Processes->save($process)) {
                $this->Flash->success(__('O processo foi clonado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível clonar o processo. Tente novamente.'));
            }
        } else {
            $process = $this->Processes->newEntity();
            $process->name = $clonedProcess->name;
            $process->initial_date = $clonedProcess->initial_date;
            $process->teacher_intent_date = $clonedProcess->teacher_intent_date;
            $process->primary_distribution_date = $clonedProcess->primary_distribution_date;
            $process->substitute_intent_date = $clonedProcess->substitute_intent_date;
            $process->secondary_distribution_date = $clonedProcess->secondary_distribution_date;
            $process->final_date = $clonedProcess->final_date;
            foreach ($clonedProcess->clazzes as $clonedClazze) {
                $clazze = $this->Clazzes->newEntity();
                $clazze->name = $clonedClazze->name;
                $clazze->$vacancies = $clonedClazze->name;
                $clazze->$subject = $clonedClazze->name;
                $clazze->$schedule = $clonedClazze->name;
                $clazze->$local = $clonedClazze->name;
                $clazze->$process = $process;
                $clazze->$teachers = $clonedClazze->$teachers;

                $process->clazzes->push($clazze);
            }
            $process->clazzes = debug($clonedProcess->clazzes);
        }

        $this->set(compact('$process'));
        $this->set('_serialize', ['$process']);
    }

    public function reuseProcess($id)
    {
        $originalProcess = $this->Processes->get($id, [
            'contain' => ['Clazzes', 'Clazzes.ClazzesTeachers', 'Clazzes.ClazzesSchedulesLocals', 'ProcessesProcessConfigurations'],
        ]);

        unset($originalProcess->id);
        $originalProcess->status = 'OPENED';
        $originalProcess->name = $originalProcess->name . '(Clonado)';

        foreach ($originalProcess->clazzes as $item => $value) {

            unset($originalProcess->clazzes[$item]->id);
            unset($originalProcess->clazzes[$item]->process_id);
            $originalProcess->clazzes[$item]->isNew(true);

            foreach ($value->intents as $i => $v) {
                unset($originalProcess->clazzes[$item]->intents[$i]->clazz_id);
                $originalProcess->clazzes[$item]->intents[$i]->isNew(true);
            }

            foreach ($value->scheduleLocals as $i => $v) {
                unset($originalProcess->clazzes[$item]->scheduleLocals[$i]->clazz_id);
                $originalProcess->clazzes[$item]->scheduleLocals[$i]->isNew(true);
            }
        }

        foreach ($originalProcess->processes_process_configurations as $item => $value) {
            unset($originalProcess->processes_process_configurations[$item]->process_id);
        }

        $clonedProcess = $this->Processes->newEntity($originalProcess->toArray(),
            ['associated' => ['Clazzes', 'Clazzes.ClazzesTeachers', 'Clazzes.ClazzesSchedulesLocals', 'ProcessesProcessConfigurations']]);

        $clonedProcess->clazzes = $originalProcess->clazzes;

        if ($this->Processes->save($clonedProcess)) {
            $this->Flash->success(__('Processo clonado com sucesso!'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao tentar clonar o Processo. Por favor, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);

    }


}