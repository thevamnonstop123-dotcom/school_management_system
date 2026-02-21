<?php
function initPagination($obj, $method, $searchMethod = null, $defaultLimit = 10) {
    $action = $_GET['action'] ?? 'list';
    $search = $_GET['search'] ?? '';
    $limit = $defaultLimit;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    
    $data = [];
    
    if ($action === 'list') {
        if (!empty($search) && $searchMethod) {
            $data['items'] = $obj->$searchMethod($search, $offset, $limit);
            $data['total'] = $obj->countSearch($search);
        } else {
            $data['items'] = $obj->$method($offset, $limit);
            $data['total'] = $obj->getTotalCount();
        }
        $data['totalPages'] = ceil($data['total'] / $limit);
        $data['page'] = $page;
        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['search'] = $search;
    }
    
    return $data;
}