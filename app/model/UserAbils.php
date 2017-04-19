<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 19.11.2015
 */

namespace app\model;

/**
 * Class UserBabil
 * @package components\Model
 *
 * @method $this|$this[] asModel()
 *
 * @property int $owner
 * @property int $magic
 * @property int $btype
 * @property int $dur
 * @property int $maxdur
 */
class UserAbils extends BaseModal
{
	protected $table = 'users_abils';
}