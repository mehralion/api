<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 19.04.17
 * Time: 14:48
 */

namespace app\model;

/**
 * Class User
 * @package app\model
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $pass
 * @property string $second_password
 * @property string $realname
 * @property string $borndate
 * @property int $sex
 * @property string $city
 * @property int $icq
 * @property string $http
 * @property string $info
 * @property string $lozung
 * @property string $color
 * @property int $level
 * @property string $align
 * @property string $klan
 * @property int $sila
 * @property int $lovk
 * @property int $inta
 * @property int $vinos
 * @property int $intel
 * @property int $mudra
 * @property int $duh
 * @property int $bojes
 * @property float $money
 * @property int $noj
 * @property int $mec
 * @property int $topor
 * @property int $dubina
 * @property int $win
 * @property int $lose
 * @property string $status
 * @property string $borncity
 * @property int $borntime
 * @property int $room
 * @property int $maxhp
 * @property int $hp
 * @property int $maxmana
 * @property int $mana
 * @property int $sergi
 * @property int $kulon
 * @property int $perchi
 * @property int $weap
 * @property int $bron
 * @property int $r1
 * @property int $r2
 * @property int $r3
 * @property int $helm
 * @property int $shit
 * @property int $boots
 * @property int $stats
 * @property int $exp
 * @property int $master
 * @property string $shadow
 * @property int $nextup
 * @property int $m1
 * @property int $m2
 * @property int $m3
 * @property int $m4
 * @property int $m5
 * @property int $m6
 * @property int $m7
 * @property int $m8
 * @property int $m9
 * @property int $m10
 * @property int $m11
 * @property int $m12
 * @property int $m13
 * @property int $m14
 * @property int $m15
 * @property int $nakidka
 * @property int $mfire
 * @property int $mwater
 * @property int $mair
 * @property int $mearth
 * @property int $mlight
 * @property int $mgray
 * @property int $mdark
 * @property int $fullhptime
 * @property int $zayavka
 * @property int $battle
 * @property int $battle_t
 * @property int $block
 * @property int $palcom
 * @property int $medals
 * @property int $ip
 * @property int $podarokAD
 * @property int $lab
 * @property int $bot
 * @property int $in_tower
 * @property float $ekr
 * @property int $chattime
 * @property string $sid
 * @property int $fullmptime
 * @property int $deal
 * @property string $married
 * @property int $injury_possible
 * @property int $labzay
 * @property int $fcount
 * @property int $rep
 * @property int $repmoney
 * @property int $last_battle
 * @property int $vk_user_id
 * @property int $bpzay
 * @property int $bpalign
 * @property int $bpstor
 * @property int $bpbonussila
 * @property int $bpbonushp
 * @property string $show_advises
 * @property int $hidden
 * @property int $battle_fin
 * @property string $gruppovuha
 * @property int $autofight
 * @property float $expbonus
 * @property int $wcount
 * @property int $victorina
 * @property int $id_grup
 * @property int $prem
 * @property int $hiller
 * @property int $khiller
 * @property int $slp
 * @property int $trv
 * @property int $ldate
 * @property int $stamina
 * @property int $odate
 * @property int $id_city
 * @property int $ruines
 * @property int $voinst
 * @property int $rubashka
 * @property int $stbat
 * @property int $winstbat
 * @property int $citizen
 * @property int $skulls
 * @property string $hiddenlog
 * @property int $naim
 * @property int $naim_war
 * @property int $pasbaf
 * @property int $runa1
 * @property int $runa2
 * @property int $runa3
 * @property int $is_sn
 * @property int $elkbat
 * @property int $smagic
 * @property int $unikstatus
 * @property int $change
 * @property int $rep_bonus
 * @property int $gold
 * @property int $znak
 * @property int $buketbat
 */
class User extends BaseModal
{
	protected $table = 'users';

	public $primaryKey = 'id';
}