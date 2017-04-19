<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 19.11.2015
 */

namespace app\model;

/**
 * Class Bank
 * @package components\Model
 *
 * @method $this|$this[] asModel()
 *
 * @property int $id
 * @property int $type
 * @property int $item_id
 * @property string $name
 * @property string $img
 * @property int $room
 * @property string $present
 * @property int $extra
 * @property int $durability
 *
 */
class RuinesItems extends BaseModal
{
	protected $table = 'ruines_items';
	public $primaryKey = 'id';
}