<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 19.11.2015
 */

namespace app\model;

/**
 * Class Effect
 * @package components\Model
 *
 * @method $this|$this[] asModel()
 *
 * @property int $id
 * @property int $type
 * @property string $name
 * @property int $time
 * @property int $sila
 * @property int $lovk
 * @property int $inta
 * @property int $vinos
 * @property int $intel
 * @property int $owner
 * @property int $lastup
 * @property int $idiluz
 * @property int $pal
 * @property string $add_info
 * @property int $battle
 * @property int $eff_bonus
 *
 */
class Effect extends BaseModal
{
	protected $table = 'effects';
	public $primaryKey = 'id';
}