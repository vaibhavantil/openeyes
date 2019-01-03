<?php

class ContextController extends BaseAdminController
{
   // public $defaultAction = 'firms';

    public function actionIndex()
    {
        Audit::add('admin-Firm', 'list');
        $search = \Yii::app()->request->getPost('search', ['query' => '', 'active' => '']);
        $criteria = new \CDbCriteria();

        if (Yii::app()->request->isPostRequest) {
            if ($search['query']) {
                if (is_numeric($search['query'])) {
                    $criteria->addCondition('id = :id');
                    $criteria->params[':id'] = $search['query'];
                } else {
                    $criteria->addSearchCondition('pas_code', $search['query'], true, 'OR');
                    $criteria->addSearchCondition('cost_code', $search['query'], true, 'OR');
                    $criteria->addSearchCondition('name', $search['query'], true, 'OR');
                }
            }

            if ($search['active'] == 1) {
                $criteria->addCondition('active = 1');
            } elseif ($search['active'] !== '') {
                $criteria->addCondition('active != 1');
            }
        }

        $this->render('index', array(
            'pagination' => $this->initPagination(Firm::model(), $criteria),
            'firms' => Firm::model()->findAll($criteria),
            'search' => $search
        ));
    }

    public function actionAdd()
    {
        $firm = new Firm();

        if (!empty($_POST)) {
            $firm->attributes = $_POST['Firm'];

            if (!$firm->validate()) {
                $errors = $firm->getErrors();
            } else {
                if (!$firm->save()) {
                    throw new Exception('Unable to save firm: ' . print_r($firm->getErrors(), true));
                }
                Audit::add('admin-Firm', 'add', $firm->id);
                $this->redirect('/Admin/context/' . ceil($firm->id / $this->items_per_page));
            }
        }

        $this->render('edit', array(
            'firm' => $firm,
            'errors' => @$errors,
            'subspecialties_list_data' => CHtml::listData(Subspecialty::model()->findAll(['order' => 'name']), 'id', 'name'),
            'consultant_list_data' => CHtml::listData(User::model()->findAll(['order' => 'first_name,last_name']), 'id', 'fullName'),
        ));
    }

    public function actionEdit($id)
    {
        $firm = Firm::model()->findByPk($id);
        if (!$firm) {
            throw new Exception("Firm not found: $id");
        }

        if (!empty($_POST)) {
            $firm->attributes = $_POST['Firm'];

            if (!$firm->validate()) {
                $errors = $firm->getErrors();
            } else {
                if (!$firm->save()) {
                    throw new Exception('Unable to save firm: ' . print_r($firm->getErrors(), true));
                }
                Audit::add('admin-Firm', 'edit', $firm->id);
                $this->redirect('/Admin/context/' . ceil($firm->id / $this->items_per_page));
            }
        } else {
            Audit::add('admin-Firm', 'view', $id);
        }

        $site_secretaries = array();
        if (isset(Yii::app()->modules['OphCoCorrespondence'])) {
            $firmSiteSecretaries = new FirmSiteSecretary();
            $site_secretaries = $firmSiteSecretaries->findSiteSecretaryForFirm($id);
            $firmSiteSecretaries->firm_id = $id;
            $site_secretaries[] = $firmSiteSecretaries;
        }

        $this->render('edit', array(
            'firm' => $firm,
            'errors' => @$errors,
            'site_secretaries' => $site_secretaries,
            'subspecialties_list_data' => CHtml::listData(Subspecialty::model()->findAll(['order' => 'name']), 'id', 'name'),
            'consultant_list_data' => CHtml::listData(User::model()->findAll(['order' => 'first_name,last_name']), 'id', 'fullName'),

        ));
    }
}