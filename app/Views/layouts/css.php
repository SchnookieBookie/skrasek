<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    
    /* Přepsání výchozího CI4 stránkování aby vypadalo jako Bootstrap */
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.375rem;
    }
    .pagination li {
        margin: 0 2px;
    }
    .pagination li a, .pagination li span {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        color: #f8fafc;
        text-decoration: none;
        background-color: #212529;
        border: 1px solid #343a40;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    .pagination li.active a, .pagination li.active span {
        z-index: 3;
        color: #fff;
        background-color: #198754;
        border-color: #198754;
    }
    .pagination li a:hover {
        background-color: #2c3034;
        border-color: #495057;
    }
</style>