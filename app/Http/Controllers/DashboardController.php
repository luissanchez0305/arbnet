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
            else if($process_index == 3 && $check_process && ($order_process->status_id == '6')){
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

    public function get_processes(Request $request){
        $processes = null;
        if($request->type_id == '-1')
            $processes = \App\Arbitrage_Process::all();
        else
            $processes = \App\Arbitrage_Process::where('type_id', '=', $request->type_id)->get();
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


}