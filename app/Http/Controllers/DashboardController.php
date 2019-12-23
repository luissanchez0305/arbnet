<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DashboardController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {}

    public function get_user_balance(Request $request){
        $user_data = DB::table('users')
            ->where('id', '=', $request->user_id)
            ->get()
            ->first();
        echo json_encode(array('balance_btc' => $user_data->balance_btc, 'balance_eth' => $user_data->balance_eth));
    }

    public function get_balances(Request $request){
        $order_processes_close = DB::table('arbitrage_orders')
            ->join('arbitrage_processes', 'arbitrage_orders.process_id', '=', 'arbitrage_processes.id')
            ->where('type_id', '=', '2')
            ->orderBy('arbitrage_orders.process_id', 'asc')
            ->orderBy('arbitrage_orders.updated_date', 'asc')
            ->get();
        $order_processes_open =  DB::table('arbitrage_orders')
            ->join('arbitrage_processes', 'arbitrage_orders.process_id', '=', 'arbitrage_processes.id')
            ->where('type_id', '=', '1')
            ->orderBy('arbitrage_orders.process_id', 'asc')
            ->orderBy('arbitrage_orders.updated_date', 'asc')
            ->get();

        /*
        - limit source created = 2
        - limit source completed = 3
        - limit target created = 6
        - limit target completed = 7
        */
        $process_id = 0;
        $commission = 0;
        $check_process = false;
        $buy_amount = 0;
        $sell_amount = 0;
        $earning_close_eth = 0;
        $earning_open_eth = 0;
        $earning_close_btc = 0;
        $earning_open_btc = 0;
        $process_index = 1;

        foreach ($order_processes_close as $order_process) {
            if($process_id != $order_process->process_id){
                $process_id = $order_process->process_id;
                $commission = 0;
                if($order_process->status_id == 3){
                    $check_process = true;
                    $commission = $order_process->commission;
                    $buy_amount = $order_process->price * $order_process->quantity;
                    $process_index += 1;
                }
                else
                    $check_process = false;
            }
            else if($process_index == 2 && $check_process && $order_process->status_id != '6' && $order_process->status_id != '7'){
                $commission += $order_process->commission;
                $process_index += 1;
            }
            else if($process_index == 3 && $check_process && ($order_process->status_id == '7')){
                $commission += $order_process->commission;
                $sell_amount = $order_process->price * $order_process->quantity;
                if(substr($order_process->symbol, strlen($order_process->symbol) - 3) == 'ETH')
                    $earning_close_eth += $sell_amount - $buy_amount - $commission;
                else
                    $earning_close_btc += $sell_amount - $buy_amount - $commission;
                $process_index = 1;
            }
        }

        $process_id = 0;
        $buy_amount = 0;
        $sell_amount = 0;
        $process_index = 1;
        foreach ($order_processes_open as $order_process) {
            if($process_id != $order_process->process_id){
                $process_id = $order_process->process_id;
                $commission = 0;
                if($order_process->status_id == '3'){
                    $check_process = true;
                    $commission = $order_process->commission;
                    $buy_amount = $order_process->price * $order_process->quantity;
                    $process_index += 1;
                }
                else
                    $check_process = false;
            }
            else if($process_index == 2 && $check_process && $order_process->status_id != '6' && $order_process->status_id != '7'){
                $commission += $order_process->commission;
                $process_index += 1;
            }
            else if($process_index == 3 && $check_process && $order_process->status_id == '6'){
                $commission += $order_process->commission;
                $sell_amount = $order_process->price * $order_process->quantity;
                if(substr($order_process->symbol, strlen($order_process->symbol) - 3) == 'ETH')
                    $earning_open_eth += $sell_amount - $buy_amount - $commission;
                else
                    $earning_open_btc += $sell_amount - $buy_amount - $commission;
                $process_index = 1;
            }
        }
        $earning_open_btc = number_format($earning_open_btc, 8);
        $earning_close_btc = number_format($earning_close_btc, 8);
        $earning_open_eth = number_format($earning_open_eth, 8);
        $earning_close_eth = number_format($earning_close_eth, 8);
        return view('partials.balances_table', compact('earning_open_btc', 'earning_close_btc', 'earning_open_eth', 'earning_close_eth'));
    }

    public function convert_currency(Request $request){
        $url_btc = 'https://min-api.cryptocompare.com/data/histominute?fsym=BTC&tsym=USD&limit=1&aggregate=1';
        $url_eth = 'https://min-api.cryptocompare.com/data/histominute?fsym=ETH&tsym=USD&limit=1&aggregate=1';
        $payload_btc = file_get_contents($url_btc);
        $payload_eth = file_get_contents($url_eth);
        $parsed_btc = json_decode($payload_btc);
        $parsed_eth = json_decode($payload_eth);
        echo json_encode(array('btc' => $parsed_btc->Data['1']->close,'eth' => $parsed_eth->Data['1']->close));
    }

    public function get_processes(Request $request){
        $processes_obj = null;
        if($request->type_id == '-1')
            $processes_obj = \App\Arbitrage_Process::all();
        else
            $processes_obj = \App\Arbitrage_Process::where('type_id', '=', $request->type_id)->get();

        $processes = [];
        foreach ($processes_obj as $process) {
            $price = $this->get_price($process->symbol, $process->exchange_target);
            $orders = \App\Arbitrage_Order::where('process_id', '=', $process->id)->orderBy('updated_date', 'DESC')->get();
            $order_source = null;
            $order_target = null;
            $order_type_max = 0;
            foreach ($orders as $order) {
                if($order_target == null && $order->status_id == '7' || $order_target == null && $order->status_id == '6')
                    $order_target = $order;
                else if($order_source == null && $order->status_id == '3' || $order_source == null && $order->status_id == '2' )
                    $order_source = $order;

                if($order->status_id > $order_type_max)
                    $order_type_max = $order->status_id;
            }
            $process_margin = number_format(($order_target->quantity*$order_target->price)-($order_source->quantity*$order_source->price),8);
            $current_margin = number_format(($order_target->quantity*$price)-($order_source->quantity*$order_source->price), 8);
            array_push($processes, array(
                'id' => $process->id,
                'symbol' => $process->symbol,
                'exchange_source'=>$process->exchange_source,
                'exchange_target'=>$process->exchange_target,
                'type_id'=>$process->type_id,
                'exchange_target_price'=>number_format($price, 8),
                'order_target_price'=>number_format($order_target->price, 8),
                'current_margin'=>$process->type_id == '2' ? $process_margin : $current_margin,
                'process_margin'=>$process_margin,
                'target_qty' => $order_target->quantity,
                'source_qty'=>$order_source->quantity,
                'earning'=>number_format(((number_format(($process->type_id == '2' ? $order_target->price : $price), 8)/number_format($order_source->price, 8))*100) - 100,2),
                'progress'=>(int)((($order_type_max/7)*100)),
                'max'=>$order_type_max,
                'created_date'=>$process->created_date,
                'updated_date'=>$process->updated_date));
        }
        return view('partials.processes_table', compact('processes'));
    }

    public function get_orders(Request $request){
        $orders = DB::table('arbitrage_orders')
            ->join('order_status', 'arbitrage_orders.status_id', '=', 'order_status.id')
            ->select(DB::Raw('arbitrage_orders.*,order_status.name'))
            ->where('arbitrage_orders.process_id', '=', $request->process_id)
            ->get();
        $symbol = $request->symbol;
        $exchange_source = $request->source;
        $exchange_target = $request->target;
        return view('partials.orders_table', compact('orders','symbol','exchange_source','exchange_target'));
    }

    public function get_price($symbol, $exchange){
        $price = 0;
        switch ($exchange) {
            case 'BITTREX':
                # code...
                break;
            case 'COINBASE':
                # code...
                break;
            case 'HITBTC':
                # code...
                break;
            case 'BITFINEX':
                $result = $this->run_curl('https://api-pub.bitfinex.com/v2/ticker/t' . $symbol);
                $json = json_decode($result, true);

                $price = $json[6];
                break;
            case 'BINANCE':
                # code...
                break;
            case 'FATBTC':
                $result = $this->run_curl('https://www.fatbtc.us/m/allticker/1/');
                $json = json_decode($result, true);

                foreach ($json['data'] as $tick) {
                    if(strtoupper($tick['symbol']) == $symbol)
                        $price = $tick['close'];
                }
                break;
            case 'OKEX':
                # code...
                break;
            case 'DIGIFINEX':
                # code...
                break;
            default:
                # code...
                break;
        }
        //close connection
        return $price;
    }

    private function run_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //needed so that the $result=curl_exec() output is the file and isn't just true/false
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }
}