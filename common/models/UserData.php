<?php

namespace common\models;

use Yii;
use yii\validators\DateValidator;

/**
 * This is the model class for table "userData".
 *
 * @property int $user_id
 * @property string $fName
 * @property string $surname
 * @property string $birthdate
 * @property string $phone
 * @property string $nif
 * @property string $gender
 * @property string $accCreationDate
 *
 * @property User $user
 */
class UserData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userData';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'fName', 'surname', 'birthdate', 'phone', 'nif', 'gender', 'accCreationDate'], 'required'],
            [['user_id'], 'integer'],
            [['birthdate', 'accCreationDate'], 'safe'],
            [['gender'], 'string'],
            ['gender', 'in', 'range' => ['M', 'F']],
            [['fName', 'surname'], 'string','min' => 2, 'max' => 25],
            [['phone', 'nif'], 'string', 'min' => 9, 'max' => 9],
            [['phone', 'nif', 'user_id'], 'unique'],
            [['phone', 'nif'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'fName' => 'First Name',
            'surname' => 'Surname',
            'birthdate' => 'Birthdate',
            'phone' => 'Phone',
            'nif' => 'NIF',
            'gender' => 'Gender',
            'accCreationDate' => 'Acc Creation Date',
        ];
    }

    /**
     * Generates fName and sets it to the model
     *
     * @param string $fName
     */
    public function setFirstName($fName)
    {
        $this->fName = $fName;
    }

    /**
     * Generates surname and sets it to the model
     *
     * @param string $Surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Generates birthdate and sets it to the model
     *
     * @param string $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

/**
     * Generates phone and sets it to the model
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Generates nif and sets it to the model
     *
     * @param string $nif
     */
    public function setNif($nif)
    {
        $this->nif = $nif;
    }

    /**
     * Generates gender and sets it to the model
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
