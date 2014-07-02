<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SetOrderController
 *
 * @author aguilangeles@gmail.com
 */
class SetOrderController extends Controller {

    public function actionSetOrder() {

        MongoCursor::$timeout = -1;
        $oldPos = (int) $_POST['oldPos'];
        $newPos = (int) $_POST['newPos'];
        $id = $_POST['id'];

        $levels = array();
        $levels[] = array('c1', isset($_POST['c1']) ? $_POST['c1'] : '');
        $levels[] = array('c2', isset($_POST['c2']) ? $_POST['c2'] : '');
        $levels[] = array('c3', isset($_POST['c3']) ? $_POST['c3'] : '');
        $levels[] = array('c4', isset($_POST['c4']) ? $_POST['c4'] : '');

        $criteria = new EMongoCriteria();

        foreach ($levels as $level) {
            if (!empty($level[1])) {
                $criteria->addCond($level[0], '==', $level[1]);
            }
        }

        $ids = $this->getIds($levels, $oldPos);

        $criteria->sort('idPapel', EMongoCriteria::SORT_DESC);
        $records = Idc::model()->findAll($criteria);

        $qty = count($records);

        if ($newPos > $qty) {
            echo "Error, el valor no puede ser mas grande que " . $qty;
        } else if ($newPos < 1) {
            echo "Error, el valor no puede ser menor que 0";
        } else {

            $criteria->setSort(array('idPapel' => EMongoCriteria::SORT_ASC));
            if ($oldPos > $newPos) {//se mueve para abajo
                $criteria->addCond('idPapel', '<', $oldPos);
                $criteria->addCond('idPapel', '>=', $newPos);
                $modifier = new EMongoModifier();
                $modifier->addModifier('idPapel', 'inc', 1);
                $status = Idc::model()->updateAll($modifier, $criteria);
                $criteria = new EMongoCriteria();
                $modifier = new EMongoModifier();
                foreach ($ids as $id) {
                    $criteria->addCond('_id', '==', new MongoID($id));
                    $modifier->addModifier('idPapel', 'set', $newPos);
                    $status = Idc::model()->updateAll($modifier, $criteria);
                }
            } else {
                $criteria->addCond('idPapel', '>', $oldPos);
                $criteria->addCond('idPapel', '<=', $newPos);
                //se les resta uno
                $modifier = new EMongoModifier();
                $modifier->addModifier('idPapel', 'inc', -1);
                $status = Idc::model()->updateAll($modifier, $criteria);
                $criteria = new EMongoCriteria();
                $modifier = new EMongoModifier();
                foreach ($ids as $id) {
                    $criteria->addCond('_id', '==', new MongoID($id));
                    $modifier->addModifier('idPapel', 'set', $newPos);
                    $status = Idc::model()->updateAll($modifier, $criteria);
                }
            }
        }
    }
    private function getIds($levels, $oldPos) {
        $result = array();
        $criteria = new EMongoCriteria();
        foreach ($levels as $level) {
            if (!empty($level[1])) {
                $criteria->addCond($level[0], '==', $level[1]);
            }
        }
        $criteria->addCond('idPapel', '==', $oldPos);
        $records = Idc::model()->findAll($criteria);
        foreach ($records as $record) {
            array_push($result, $record->_id);
        }
        return $result;
    }
}
