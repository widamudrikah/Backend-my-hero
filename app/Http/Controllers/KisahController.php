<?php

namespace App\Http\Controllers;

use App\Models\Kisah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KisahController extends Controller
{
    //Mendapatkan semua data kisah
    public function index()
    {
        $kisah = Kisah::get()
                ->map(function($kisah){
                    return $this->format($kisah);
                });

        return $this->result($kisah, "Berhasil mendapatkan semua data kisah");
       
    }

    //Menambah data kisah
    public function create(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'judul'     => 'required',
            'kisah'     => 'required',
            'gambar'    => 'required',
            'sub_judul' => 'required',
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return $this->error(false, $valIndex[0]);
        }

        $kisah = Kisah::create([
            'judul'     => $request->judul,
            'kisah'     => $request->kisah,
            'sub_judul' => $request->sub_judul,
            'gambar'    => $request->file('gambar')->store('img-kisah')
        ]);

        $kisah = Kisah::where('id', $kisah->id)
                ->get()
                ->map(function($kisah){
                    return $this->format($kisah);
                });
        return $this->result($kisah, "Berhasil menambahkan data kisah");

    }


    //Mendapatkan detail data kisah

    public function show($id)
    {
        $kisah = Kisah::where('id', $id)
                ->get()
                ->map(function ($kisah){
                    return $this->format($kisah);
                });
        return $this->result($kisah, "Berhasil mendapatkan detail kisah");
    }



    public function update(Request $request, $id)
    {
        $kisah = Kisah::where('id', $id)->first();

        if(!$kisah){
            return response([
                'Status'    => false,
                'Message'   => "Data tidak ditemukan"
            ]);
        }

        $validasi = Validator::make($request->all(), [
            'judul'     => 'required',
            'kisah'     => 'required',
            'gambar'    => 'required',
            'sub_judul' => 'required',
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return $this->error(false, $valIndex[0]);
        }

        $kisah->update([
            'judul'     => $request->judul,
            'kisah'     => $request->kisah,
            'sub_judul'     => $request->sub_judul,
            'gambar'    => $request->file('gambar')->store('img-kisah')
        ]);

        $kisah = Kisah::where('id', $kisah->id)
                ->get()
                ->map(function($kisah){
                    return $this->format($kisah);
                });
        return $this->result($kisah, "Berhasil update kisah");
    }


    public function delete($id)
    {
        $kisah = Kisah::find($id);

        if (!$kisah) {
            return $this->error(false, "Data tidak ditemukan");
        }
    
        // Menghapus file gambar dari sistem file
        $gambarPath = public_path($kisah->gambar);
        if (file_exists($gambarPath)) {
            unlink($gambarPath);
        }
    
        $kisah->delete();
    
        return $this->result(null, "Berhasil menghapus data kisah beserta gambar");
    }

    public function result($kisah, $message){
        return response()->json([
            'Status'        => true,
            'Message'       => $message,
            'Data'          => $kisah
        ]);
    }

    public function format($kisah){
        return[
            'id'        =>$kisah->id,
            'judul'     =>$kisah->judul,
            'sub_judul'    =>$kisah->sub_judul,
            'kisah'     => $kisah->kisah,
            'gambar'    => $kisah->gambar,
            'uploaded'    => date("l, d F Y", strtotime($kisah->created_at)),
        ];
    }

    public function error($status, $message){
        return response()->json([
            'Status'        => $status,
            'Message'       => $message
        ]);
    }
}
