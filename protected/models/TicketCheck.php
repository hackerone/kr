<?php

/**
 * This is the model class for table "ticket_check".
 *
 * The followings are the available columns in table 'ticket_check':
 * @property integer $id
 * @property string $ticket_id
 * @property string $checkitem_id
 * @property string $user_id
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Ticket $ticket
 * @property Checkitem $checkitem
 */
class TicketCheck extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket_check';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_id, checkitem_id, user_id, created_date', 'safe'),
			array('ticket_id, checkitem_id, user_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ticket_id, checkitem_id, user_id, created_date', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'ticket' => array(self::BELONGS_TO, 'Ticket', 'ticket_id'),
			'checkitem' => array(self::BELONGS_TO, 'Checkitem', 'checkitem_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ticket_id' => 'Ticket',
			'checkitem_id' => 'Checkitem',
			'user_id' => 'User',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('ticket_id',$this->ticket_id,true);
		$criteria->compare('checkitem_id',$this->checkitem_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketCheck the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function updateChecks($ticket_id, $checkItems, $user_id)
	{
		$c = new CDbCriteria;
		$c->compare('ticket_id', $ticket_id);
		$c->addNotInCondition('checkitem_id', $checkItems);
		$this->deleteAll($c);

		foreach($checkItems as $item){
			$this->upsert(['ticket_id' => $ticket_id, 'checkitem_id' => $item], ['user_id' => $user_id]);
		}
	}

	public function upsert($cond, $att)
	{
		$model = $this->findByAttributes($cond);
		if(!$model){
			$model = new self();
			$model->attributes = $cond;
		}
		$model->attributes = $att;
		return $model->save();
	}

	public function beforeSave()
	{
		if($this->isNewRecord){
			$this->created_date = new CDbExpression('NOW()');
		}
		return parent::beforeSave();
	}
}
