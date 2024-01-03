<?php

function get_real_price($json, $server_id, $account_type)
{
  if (is_string($json)) {
    $json = json_decode($json);
  }
  $price = 0;
  $server_id = 'sv_id_' . $server_id;
  if (!empty($json->{$server_id})) {
    $price_extra = $json->{$server_id};
    if (!empty($price_extra->price->{$account_type})) {
      $price = $price_extra->price->{$account_type};
    } else if (!empty($price_extra->price->default)) {
      $price = $price_extra->price->default;
    }
  } else {
    if (!empty($json->{$account_type})) {
      $price = $json->{$account_type};
    } else if (!empty($json->default)) {
      $price = $json->default;
    }
  }
  return $price;
}

function get_real_amount($json, $server_id, $account_type)
{
  if (is_string($json)) {
    $json = json_decode($json);
  }

  $amount = new stdClass;
  $amount->min = 0;
  $amount->max = 0;

  $server_id = 'sv_id_' . $server_id;
  if (!empty($json->{$server_id})) {
    $amount_extra = $json->{$server_id};
    if (!empty($amount_extra->amount->{$account_type})) {
      $amount = $amount_extra->amount->{$account_type};
    } else if (!empty($amount_extra->amount->default)) {
      $amount = $amount_extra->amount->default;
    }
  }else {
    if (!empty($json->{$account_type})) {
      $amount = $json->{$account_type};
    } else if (!empty($json->default)) {
      $amount = $json->default;
    }
  }
  return $amount;
}
