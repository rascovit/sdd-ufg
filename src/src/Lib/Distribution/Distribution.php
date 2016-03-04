<?php

namespace App\Lib\Distribution;

use Cake\ORM\TableRegistry;

class Distribution
{
    public static function generateDistribution($clazzes, $teachers)
    {
		foreach($clazzes as $clazz){
			if(!empty($clazz->teachers)){
				continue;
			}
			$teachersByKnowledge = self::getTeacherByKnowledge($clazz['subject']['knowledge_id'], $teachers);
			if(count($teachersByKnowledge) > 0){
				$clazz->teachers =[];
				$clazz->teachers[] = self::getTeacherByMinorCurrentWorkload($teachersByKnowledge);
				//Não é possível o update do atributo virtual para isso será criado uma variavel
				//que irá armazenar o workload de cada disciplina
				self::updateTeacherDistributionWorkload($clazz);
			}else{
				$clazz->teachers =[];
				$clazz->teachers[] = self::getTeacherByMinorCurrentWorkload($teachers);
				self::updateTeacherDistributionWorkload($clazz);
			}
		}
		
        return $clazzes;
    }
	
	public static function getTeacherByKnowledge($knowledgeId, $teachers){
		$teachersByKnowledge = [];
		foreach($teachers as $teacher){
			foreach($teacher->knowledges as $knowledge){
				if($knowledge->id == $knowledgeId && ($teacher->current_workload + $teacher->distributionWorkload) < $teacher->workload){
					$teachersByKnowledge[] = $teacher;
				}
			}
		}
		return $teachersByKnowledge;
	}
	
	public static function getTeacherByMinorCurrentWorkload($teachers){
		usort($teachers, ['App\Lib\Distribution\Distribution', 'sortByCurrentWorkload']);
		return $teachers[0];
	}
	
	public static function sortByCurrentWorkload($teacher1, $teacher2)
    {
        if (($teacher1->current_workload + $teacher1->distributionWorkload)  === ($teacher2->current_workload + $teacher2->distributionWorkload)) {
            return 0;
        }
        return ($teacher1->current_workload + $teacher1->distributionWorkload) < ($teacher2->current_workload + $teacher2->distributionWorkload) ? -1 : 1;
    }
	
	public static function updateTeacherDistributionWorkload($clazz){
		$clazz->teachers[0]->distributionWorkload += ($clazz->subject->theoretical_workload + $clazz->subject->practical_workload) / 16;
	}
}