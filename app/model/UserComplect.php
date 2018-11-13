<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 19.11.2015
 */

namespace app\model;

/**
 * Class UserComplect
 * @package components\Model
 *
 * @method $this|$this[] asModel()
 *
 * @property int $id
 * @property int $owner
 * @property string $name
 * @property string $data
 * @property int $id_city
 *
 */
class UserComplect extends BaseModal
{
	protected $table = 'users_complect2';
	public $primaryKey = 'id';
}