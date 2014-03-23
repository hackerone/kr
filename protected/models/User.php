<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property integer $rights
 *
 * The followings are the available model relations:
 * @property TicketCheck[] $ticketChecks
 */
class User extends CActiveRecord
{

	private $_oldpassword;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['username, password, rights', 'required'],
			['username', 'unique'],
			['rights', 'numerical', 'integerOnly'=>true],
			['username', 'length', 'max'=>40],
			['password', 'length', 'max'=>255],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, username, password, rights', 'safe', 'on'=>'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ticketChecks' => array(self::HAS_MANY, 'TicketCheck', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'User ID',
			'password' => 'Password',
			'rights' => 'Rights',
			'rightName' => 'Rights'
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('rights',$this->rights);

		$criteria->compare('rights', '>-1');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	public function afterFind()
	{
		
		$this->_oldpassword = $this->password;
		return parent::afterFind();
	}

	public function passwordChanged()
	{
		return $this->_oldpassword != $this->password;
	}

	public function beforeSave()
	{
			if($this->passwordChanged()){
				$this->password = crypt($this->password);
			}

			return parent::beforeSave();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTypes()
	{
			return [ 0 => 'User', 1 => 'Admin', -1 => 'Inactive'];
	}

	public function getRightName()
	{
			return $this->types[$this->rights];
	}

	public function login()
	{
			$identity = new UserIdentity($this->username, $this->password);
			if($identity->authenticate()){
				Yii::app()->user->login($identity);
				return true;
			}else{
				$this->addError('username', 'Invalid Username / Password');
				return false;
			}
	}
}
