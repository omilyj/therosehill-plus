<?php

function rhp_rest_event_query($args, $request){
    $orderBy = $request->get_param('orderByStartDate');
    if (isset($orderBy)) {
      $args['orderby'] = 'meta_value';
      $args['meta_key'] = 'event_start_date';
    }
    return $args;
  }