<?php

/**
 * This is the model class for table "checkitem".
 *
 * The followings are the available columns in table 'checkitem':
 * @property string $id
 * @property string $list_id
 * @property string $content
 * @property integer $status
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property Checklist $list
 * @property TicketCheck[] $ticketChecks
 */
class Checkitem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'checkitem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('list_id, content, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('list_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, list_id, content, status, created_date', 'safe', 'on'=>'search'),
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
			'list' => array(self::BELONGS_TO, 'Checklist', 'list_id'),
			'ticketChecks' => array(self::HAS_MANY, 'TicketCheck', 'checkitem_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'list_id' => 'List',
			'content' => 'Items under this Checklist',
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
		$criteria->compare('list_id',$this->list_id,true);
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
	 * @return Checkitem the static model class
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
}
