<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\Utility\Hash;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $SocialAccounts
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AppUsersTable extends UsersTable
{

	public function initialize(array $config) 
    {
        parent::initialize($config);
        $this->addBehavior('Burzum/Imagine.Imagine');

        $this->hasOne('UserImage', [
            'className' => 'UserImage',
            'foreignKey' => 'user_id',
            'conditions' => [
                'UserImage.model' => 'file_storage'
            ]
        ]);

        $this->hasMany('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Sessions',
        ]);
        $this->hasMany('Coaches', [
            'foreignKey' => 'coach_id',
            'className' => 'Sessions',
        ]);
        $this->hasMany('Coach', [
            'foreignKey' => 'coach_id',
            'className' => 'Topics',
        ]);
        $this->hasMany('PaymentInfos', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        $validator
            ->allowEmpty('token');

        $validator
            ->add('token_expires', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('token_expires');

        $validator
            ->allowEmpty('api_token');

        $validator
            ->add('activation_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('activation_date');

        $validator
            ->add('tos_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('tos_date');

        $validator
            ->add('birthdate','custom',[
                    'rule' => 'adultValidation',
                    'provider' => 'table',
                    'message'=>'You must be at least 18 years old',
                ]
            );

        return $validator;
    }

    /**
     * Set role to the user given t
     *
     * @param $userId
     * @param $role
     * @return array
     */
    public function setRole($userId, $role)
    {
        $user = $this->get($userId);
        $user = $this->patchEntity($user, ['role' => $role], ['accessibleFields' => [
                '*' => true,
                'id' => false,
                'is_superuser' => false,
                'role' => true,
            ]]);
        return $this->save($user);
    }

    /**
     * Return the role list
     *
     * @return array
     */
    public function getRoleList()
    {
        $roles = (array)Configure::read('Users.roles');

        return Hash::combine($roles, '{n}.role', '{n}.description');
    }

    /**
     * Finder method for finding coches
     *
     * @return Query
     */
    public function findCoaches(Query $query, array $options)
    {
        return $query->where([
                'AppUsers.role' => 'coach',
                'AppUsers.active' => true
            ])
            ->contain('UserImage');
    }
    /**
     * Validator function to check if user is out of age
     *
     * @return boolean
     */
    public function adultValidation($check, array $context)
    {
        if ($this->get($context['data']['id'])['role'] ==='coach'){
            return date('Y-m-d',strtotime($check)) <= date('Y-m-d',strtotime("-18 years"));
        }
        return true;
    }
    
}
