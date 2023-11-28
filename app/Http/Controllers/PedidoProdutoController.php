<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Produto;
use App\PedidoProduto;

class PedidoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pedido $pedido)
    {
        $produtos = Produto::all(); 
        //$pedido->produtos; // eager loadin
        return view('app.pedido_produto.create', ['pedido' => $pedido, 'produtos' => $produtos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pedido $pedido)
    {
        $regras = [
            'produto_id' => 'exists:produtos,id',
            'quantidade' => 'required'  
        ];

        $feedback = [
            'produto_id.exists' =>'O produto informado não existe',
            'required' => 'O campo :attribute deve possuir um valor válido'
        ];

        $request->validate($regras, $feedback);  
        
        /*
        $pedidoProdutos = new PedidoProduto();
        $pedidoProdutos->pedido_id = $pedido->id;
        $pedidoProdutos->produto_id = $request->get('produto_id');
        $pedidoProdutos->quantidade = $request->get('quantidade');
        $pedidoProdutos->save();
        */

        //$pedido->produtos; //os registros do relacionamento
        /*
        $pedido->produtos()->attach(
            $request->get('produto_id'),
            ['quantidade' => $request->get('quantidade'),
            'coluna_1',
            'coluna_2']
        ); //o objeto
        */

        $pedido->produtos()->attach([
                $request->get('produto_id') => ['quantidade' => $request->get('quantidade')]
        ]);

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  PedidoProduto $id
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Pedido $pedido, Produto $produto)
    public function destroy(PedidoProduto $pedidoProduto, $pedido_id)
    {
        /*
        print_r($pedido->getAttributes());
        echo '<hr>';
        print_r($produto->getAttributes());
        */

        //echo $pedido->id.' - '. $produto->id;

        //convencional
        /*
        PedidoProduto::where([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id
        ])->delete();
        */
        //detach(delete pelo relacionamento)
        //$pedido->produtos()->detach($produto->id);    
        //pedido_id

        $pedidoProduto->delete();

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido_id]);
    }
}
