<?php
namespace TrustOceanSSL\Models;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * TrustOcean Local Certificate Model
 */
class Certificate extends EloquentModel{

    /**
     * the table for thie model
     *
     * @var string
     */
    protected $table = 'trustoceanssl_certificate';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

















}