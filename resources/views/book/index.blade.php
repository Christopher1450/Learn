<html>
<body>
    <table border=1 width="100%">
       <tr>
          <td>No</td>
          <td>Name</td>
          <td>Author</td>
          <td>Release At</td>
       </tr>
       @foreach ($books as $key => $book)
           <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $book->name }}</td>
              <td>{{ $book->author }}</td>
              <td>{{ $book->release_at }}</td>
           </tr>
       @endforeach
    </table>
</body>
</html>
