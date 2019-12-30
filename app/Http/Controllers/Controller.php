<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $source_btc = DB::table('arbitrage')->select('volume_source')->whereRaw("SUBSTRING(pair,LENGTH(pair)-2) = 'BTC'")->orderBy('volume_source','desc')->first();
        $target_btc = DB::table('arbitrage')->select('volume_target')->whereRaw("SUBSTRING(pair,LENGTH(pair)-2) = 'BTC'")->orderBy('volume_target','desc')->first();
        $source_eth = DB::table('arbitrage')->select('volume_source')->whereRaw("SUBSTRING(pair,LENGTH(pair)-2) = 'ETH'")->orderBy('volume_source','desc')->first();
        $target_eth = DB::table('arbitrage')->select('volume_target')->whereRaw("SUBSTRING(pair,LENGTH(pair)-2) = 'ETH'")->orderBy('volume_target','desc')->first();

        $max_volume_source_btc = isset($source_btc) ? $source_btc->volume_source : 0;
        $max_volume_target_btc = isset($target_btc) ? $target_btc->volume_target : 0;
        $max_volume_source_eth = isset($source_eth) ? $source_eth->volume_source : 0;
        $max_volume_target_eth = isset($target_eth) ? $target_eth->volume_target : 0;
        $max_volume_btc = $max_volume_source_btc > $max_volume_target_btc ? $max_volume_source_btc : $max_volume_target_btc;
        $max_volume_eth = $max_volume_source_eth > $max_volume_target_eth ? $max_volume_source_eth : $max_volume_target_eth;
        $last_updated = DB::table('arbitrage')->select('date_updated as date')->orderBy('date_updated','desc')->first();
        $exchanges_source = DB::table('arbitrage')->select('exchange_source as exchange')->orderBy('exchange', 'asc')->distinct()->get();
        $exchanges_target = DB::table('arbitrage')->select('exchange_target as exchange')->orderBy('exchange', 'asc')->distinct()->get();
        $exchanges = array();
        $rows = array();
        foreach($exchanges_source as $item){
        	array_push($exchanges, $item->exchange);
        }
        foreach ($exchanges_target as $item) {
        	if(!$this->check_exchange($exchanges, $item->exchange))
        		array_push($exchanges, $item->exchange);
        }
        foreach ($exchanges as $exchange_source) {
	        $items = array();
        	foreach ($exchanges as $exchange_target) {
        		$arbitrages_item = DB::table('arbitrage')->where('exchange_source', $exchange_source)->where('exchange_target', $exchange_target)->orderByRaw('(price_target/price_source) DESC')->get();
        		$pairs = array();
    			foreach ($arbitrages_item as $arbitrage) {
                    $volume_level = 1;
                    if(substr($arbitrage->pair, strlen($arbitrage->pair) - 2) == 'BTC'){
                        $volume_source_level = $this->get_volume_level($max_volume_btc,$arbitrage->volume_source);
                        $volume_target_level = $this->get_volume_level($max_volume_btc,$arbitrage->volume_target);
                        $volume_level = (int)(($volume_source_level + $volume_target_level) / 2);
                    }
                    else{
                        $volume_source_level = $this->get_volume_level($max_volume_eth,$arbitrage->volume_source);
                        $volume_target_level = $this->get_volume_level($max_volume_eth,$arbitrage->volume_target);
                        $volume_level = (int)(($volume_source_level + $volume_target_level) / 2);
                    }
    				$margin = (($arbitrage->price_target / $arbitrage->price_source) * 100) - 100;
    				array_push($pairs, array('pair' => $arbitrage->pair, 'margin' => number_format($margin, 2),
                        'volume_source' => number_format($arbitrage->volume_source,2),
                        'volume_target' => number_format($arbitrage->volume_target,2),
                        'volume_source_level' => $volume_source_level,
                        'volume_target_level' => $volume_target_level,
                        'volume_level_global' => $volume_level,
                        'date' => $arbitrage->date_updated));
				}
	    		array_push($items, array('exchange' => $exchange_target, 'pairs' => $pairs));
        	}

        	array_push($rows, array('exchange' => $exchange_source, 'items' => $items));
        }
        return view('welcome',compact('exchanges', 'rows', 'last_updated'));
    }

    private function check_exchange($array, $val){
    	foreach ($array as $item) {
    		if($item == $val)
    			return true;
    	}
    	return false;
    }

    private function get_volume_level($max, $volume){
        if($volume == 0)
            return 0;
        $division = $max / 5;
        for($level = 1; $level <= 5; $level++){
            if($volume < $division * $level)
                return $level;
        }
        return 5;
    }
}
