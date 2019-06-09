<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addMoves(int $action, String $referer)
    {
        switch ($action) {
            case 1:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '1',
                        'doc_number' => $referer
                    )
                );
                break;
            case 2:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '2',
                        'doc_number' => $referer
                    )
                );
                break;
            case 3:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '3',
                        'doc_number' => $referer
                    )
                );
                break;
            case 4:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '4',
                        'search_criteria' => $referer
                    )
                );
                break;
            case 5:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '5'
                    )
                );
                break;
            case 6:
                DB::table('moves')->insert(
                    array(
                        'user_id' => $this->id,
                        'action' => '6',
                        'doc_number' => $referer
                    )
                );
                break;
            default:
                return 0;
        }
    }

    public function checkMessages()
    {
        return DB::table('messages')->where('receiver_id','=',$this->id)->where('status','=',0)->count();
    }

    public function getNumberOfDocuments()
    {
        return Document::all()->where('user_id',$this->id)->where('status',100)->count();
    }
}
