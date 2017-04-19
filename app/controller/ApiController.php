<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 02.06.2016
 */

namespace app\controller;


use app\component\VarDumper;
use app\helper\FileHelper;
use app\model\Clan;
use app\model\ClanWarNew;
use app\model\Effect;
use app\model\Inventory;
use app\model\RuinesItems;
use app\model\RuinesMap;
use app\model\User;
use app\model\UserAbils;
use app\model\UserComplect;

class ApiController extends BaseController
{
	private $_cache_time = 10;

    public function playerAction()
    {
        $response = array();
        $hash = null;
        try {
            if($this->app->webUser->isGuest()) {
                throw new \Exception('User not found');
            }
            $user_id = $this->app->webUser->getId();

			/** @var User $User */
            $User = User::find($user_id)->toArray();
            if(!$User) {
                throw new \Exception('Invalid USER');
            }

			$response = $this->app->cache->get('api_user_player_'.$user_id);
			if($response) {
				$response['cache'] = true;
				$this->renderJSON($response);
			}


            $data = array(
                'date' => time(),
                'player' => array(
                    'id'                => (int)$User['id'],
					'align'				=> $User['align'],
                    'login'             => $User['login'],
                    'level'             => (int)$User['level'],
                    'clan'              => $User['klan'],
                    'clanstatus'        => $User['status'],
                    'hp'                => (int)$User['hp'],
                    'hpfull'            => (int)$User['maxhp'],
                    'mp'                => (int)$User['mana'],
                    'mpfull'            => (int)$User['maxmana'],
                    'exp'               => (int)$User['exp'],
                    'expup'             => (int)$User['nextup'],
                    'battle_id'         => (int)$User['battle'],
                    'war'               => 0,
                    'inventorysets'     => array(),
                    'playerbuffs'       => array(),
                    'playerabils'   	=> array(),
                    'gamepaidstatus'    => array(
                        'type' => 'none',
                        'date' => 0
                    ),
                    'clientpaidstatus'  => array(
                        'type' => 'standart',
                        'date' => 0
                    ),
					'slots' 			=> array(
						'sergi'		=> $User['sergi'] > 0 ? $User['sergi'] : false,
						'kulon'		=> $User['kulon'] > 0 ? $User['kulon'] : false,
						'perchi' 	=> $User['perchi'] > 0 ? $User['perchi'] : false,
						'weapon'	=> $User['weap'] > 0 ? $User['weap'] : false,
						'armor'		=> $User['bron'] > 0 ? $User['bron'] : false,
						'helm'		=> $User['helm'] > 0 ? $User['helm'] : false,
						'shit'		=> $User['shit'] > 0 ? $User['shit'] : false,
						'boots'		=> $User['boots'] > 0 ? $User['boots'] : false,
						'r1'		=> $User['r1'] > 0 ? $User['r1'] : false,
						'r2'		=> $User['r2'] > 0 ? $User['r2'] : false,
						'r3'		=> $User['r3'] > 0 ? $User['r3'] : false,
					),
					'stats'				=> array(
						'sila' 	=> $User['sila'],
						'lovk' 	=> $User['lovk'],
						'inta' 	=> $User['inta'],
						'vinos' => $User['vinos'],
						'intel' => $User['intel'],
						'mudra' => $User['mudra'],
					),
                ),
            );
            //get clan war
            if($User['klan']) {
            	/** @var Clan $Clan */
                $Clan = Clan::where('short', '=', $User['klan'])->first();
                if($Clan) {
                	$Clan = $Clan->toArray();
                    $ClanWar = ClanWarNew::whereRaw('agressor = ? or defender = ?', [$Clan['id'], $Clan['id']])->first();
                    if($ClanWar) {
						$ClanWar = $ClanWar->toArray();
                        $data['player']['war'] = (int)$ClanWar['id'];
                    }
                }
            }

            //get complect
            $Complect = UserComplect::whereRaw('owner = ?', [$User['id']])->get()->toArray();
            foreach ($Complect as $_item) {
                $data['player']['inventorysets'][] = array(
                    'id'    => (int)$_item['id'],
                    'name'  => $_item['name']
                );
            }

            //get baffs
            $Effects = Effect::whereRaw('owner = ? and type not in (4999, 5999, 6999) and name != ""', [$User['id']])->get()->toArray();
            foreach ($Effects as $Effect) {
                $data['player']['playerbuffs'][] = array(
                    'name'  => $Effect['name'],
                    'date'  => (int)$Effect['time'],
					'id'	=> $Effect['type'],
                );
            }

			//get abils
			$Abils = UserAbils::whereRaw('owner = ?', [$User['id']])->get()->toArray();
			foreach ($Abils as $Abil) {
				$temp = array(
					'magic_id'  => (int)$Abil['magic_id'],
					'count'  	=> (int)$Abil['allcount'],
					'expire'	=> $Abil['findata'],
					'daily'		=> false,
				);
				if($Abil['daily']) {
					$temp['daily'] = array(
						'count' => $Abil['daily'],
						'have'	=> $Abil['dailyc'],
					);
				}

				$data['player']['playerabils'][] = $temp;
			}

            //account
            $Account = Effect::whereRaw('type in (4999, 5999, 6999) and owner = ?', [$User['id']])->first();
            if($Account) {
            	$Account = $Account->toArray();
                $name = 'silver';
                if($Account['type'] == 5999) {
                    $name = 'gold';
                } elseif($Account['type'] == 6999) {
                    $name = 'platinum';
                }
                $data['player']['gamepaidstatus']['type'] = $name;
                $data['player']['gamepaidstatus']['date'] = (int)$Account['time'];
            }

            $response = array(
                'status'   => 1,
				'success' => true,
                //'crypt'     => $hash,
                'response'  => $data
            );

			$this->app->cache->set('api_user_player_'.$user_id, $response, $this->_cache_time);

        } catch (\Exception $ex) {
            $response = array(
                'status'     => 0,
				'error' => true,
                //'crypt'     => $hash,
                'message'   => 'We have some problem, try later',
            );
			FileHelper::writeException($ex, 'api');
        }

        $this->renderJSON($response);
    }

	public function questAction()
	{
		$response = array();
		$data = array();
		try {
			if($this->app->webUser->isGuest()) {
				throw new \Exception('User not found');
			}

			$user_id = $this->app->webUser->getId();
			$User = User::find($user_id)->toArray();
			if(!$User) {
				throw new \Exception('Invalid USER');
			}

			$response = $this->app->cache->get('api_user_quest_'.$user_id);
			if($response) {
				$response['cache'] = true;
				$this->renderJSON($response);
			}

			/*$Quest = $this->app->quest
				->setUser($User)
				->get();
			foreach($Quest->getDescriptionsInfo() as $_quest) {
				$data[] = array(
					'name' 			=> $_quest[1],
					'description' 	=> $_quest[2]
				);
			}*/

			$response = array(
				'status'   => 1,
				'success' => true,
				//'crypt'     => $hash,
				'response'  => $data
			);

			$this->app->cache->set('api_user_quest_'.$user_id, $response, $this->_cache_time);

		} catch (\Exception $ex) {
			$response = array(
				'status'     => 0,
				'error' => true,
				//'crypt'     => $hash,
				'message'   => 'We have some problem, try later',
			);
			FileHelper::writeException($ex, 'api');
		}

		$this->renderJSON($response);
	}

    public function inventoryAction()
    {
        $response = array();
        $hash = null;
        try {
            if($this->app->webUser->isGuest()) {
                throw new \Exception('User not found');
            }
            $user_id = $this->app->webUser->getId();

            $User = User::find($user_id)->toArray();
            if(!$User) {
                throw new \Exception('Invalid USER');
            }

			$response = $this->app->cache->get('api_user_inventory_'.$user_id);
			if($response) {
				$response['cache'] = true;
				$this->renderJSON($response);
			}

            $data = array(
                'date' => time(),
                'items' => array(),
            );

            //get items
            $Inventory = Inventory::whereRaw('owner = ?', [$User['id']])->get()->toArray();
            foreach ($Inventory as $_item) {
                $temp = array(
                    'id'            => (int)$_item['id'],
                    'name'          => $_item['name'],
                    'description'   => $_item['letter'],
                    'magic'         => array(),
                    'is_dressed'    => $_item['dressed'],
                    'expire'        => $_item['goden'] > 0 ? $_item['dategoden'] : 0,
                    'duration'      => array(
                        'current'   => $_item['duration'],
                        'max'       => $_item['maxdur'],
                    ),
					'mass'			=> $_item['massa'],
                );
                if($_item['includemagic']) {
                    $temp['magic'] = array(
                        'name'      => $_item['includemagicname'],
                        'magic'     => $_item['includemagic'],
                        'have_use'  => $_item['includemagicdex'],
                        'max_use'   => $_item['includemagicmax'],
                        'recharge'  => $_item['includemagicuses'],
                    );
                }

                $data['items'][] = $temp;
            }

            $response = array(
                'status'   => 1,
				'success' => true,
                //'crypt'     => $hash,
                'response'  => $data
            );

			$this->app->cache->set('api_user_inventory_'.$user_id, $response, $this->_cache_time);

        } catch (\Exception $ex) {
            $response = array(
                'status'     => 0,
				'error' => true,
                //'crypt'     => $hash,
				'message'   => 'We have some problem, try later',
            );

			FileHelper::writeException($ex, 'api');
        }

        $this->renderJSON($response);
    }

    public function ruineAction()
    {
        $response = array();
        $hash = null;
        try {
            if($this->app->webUser->isGuest()) {
                throw new \Exception('User not found');
            }
            $user_id = $this->app->webUser->getId();


            $User = User::find($user_id)->toArray();
            if(!$User) {
                throw new \Exception('Invalid USER');
            }

			$response = $this->app->cache->get('api_user_ruine_'.$user_id);
			if($response) {
				$response['cache'] = true;
				$this->renderJSON($response);
			}

            $data = array(
                'date'      => time(),
                'id'        => $User['ruines'] ? $User['ruines'] : 0,
                'team'      => $User['id_grup'] ? 'red' : 'blue',
                'players'   => array(),
                'traps'     => array(),
            );
            $player_ids = array();
            if($User['ruines']) {
                $Map = RuinesMap::find($User['ruines'])->toArray();

                $UserList = User::whereRaw('ruines = ? and id_grup = ?', [$User['ruines'], $User['id_grup']])->get()->toArray();
                foreach ($UserList as $_user) {
                    $frozen = -1;
					/** @var Effect $EffectPuti */
                    $EffectPuti = Effect::whereRaw('name = "Путы" and type = 10 and owner = ?', [$_user['id']])->first();
                    if($EffectPuti) {
                    	$EffectPuti = $EffectPuti->toArray();
                        $frozen = ($EffectPuti['time'] - time()) / 60;
                    }

                    $Inventory = Inventory::whereRaw('owner = ? and bs_owner = 2', [$_user['id']])->get()->toArray();
                    $items = array();
                    foreach ($Inventory  as $_inventory) {
                        $items[] = array(
                            'title' => $_inventory['name'],
                            'img'   => $_inventory['img'],
                            'type'  => str_replace('.gif', '', $_inventory['img']),
                        );
                    }

                    $room = $_user['room'] - $Map['rooms'];

                    $player = array(
                        'id'                => (int)$_user['id'],
                        'login'             => $_user['login'],
                        'level'             => (int)$_user['level'],
                        'align'             => $_user['align'],
                        'clan'              => $_user['klan'],
                        'hp'                => (int)$_user['hp'],
                        'hpfull'            => (int)$_user['maxhp'],
                        'ruineslocation'    => $this->app->ruine[$room][0],
                        'battle'            => (int)$_user['battle'],
                        'frozen'            => $frozen,
                        'items'             => $items,
                    );

                    $data['players'][] = $player;
                    $player_ids[] = (int)$_user['id'];
                }

                /** @var RuinesItems[] $Items */
                $Items = RuinesItems::whereIn('extra', $player_ids)
					->whereRaw('type = 1 and name = "Ловушка"')
					->get()->toArray();
                foreach ($Items as $_item) {
                    $room = $_item['room'] - $Map['rooms'];
                    $data['traps'][] = $this->app->ruine[$room][0];
                }
            }

            $response = array(
                'status'   => 1,
				'success' => true,
                //'crypt'     => $hash,
                'response'  => $data
            );

			$this->app->cache->set('api_user_ruine_'.$user_id, $response, $this->_cache_time);

        } catch (\Exception $ex) {
            $response = array(
                'status'     => 0,
				'error' => true,
                //'crypt'     => $hash,
				'message'   => 'We have some problem, try later',
            );
			FileHelper::writeException($ex, 'api');
        }

        $this->renderJSON($response);
    }

}