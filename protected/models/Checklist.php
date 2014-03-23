<?php

/**
 * This is the model class for table "checklist".
 *
 * The followings are the available columns in table 'checklist':
 * @property string $id
 * @property string $name
 * @property string $content
 * @property integer $status
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property Checkitem[] $checkitems
 */
class Checklist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'checklist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, content, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, content, status, created_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'checkitems' => array(self::HAS_MANY, 'Checkitem', 'list_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'content' => 'Content',
			'status' => 'Status',
			'created_date' => 'Created Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_date',$this->created_date,true);

		$criteria->compare('status', '>0');
		
		$criteria->order = 'created_date DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Checklist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function beforeSave()
	{
		if($this->isNewRecord){
			$this->created_date = new CDbExpression("NOW()");
		}
		return parent::beforeSave();
	}

	public function getStates()
	{
		return [0 => 'Inactive', 1 => 'Active'];
	}

	public function getItemModel()
	{
		 $model = new Checkitem('search');
		 $model->list_id = $this->id;
		 return $model;
	}

	public function listAll()
	{
		$c = new CDbCriteria;
		$c->compare('status', '>0');
		$c->order = 'created_date DESC';
		return CHtml::listData($this->findAll($c), 'id', 'name');
	}
}
