<h1>{{$title}}</h1>

<ul>
    @foreach ($pets as $pet)
        <li>{{$pet}}</li>
    @endforeach
    
</ul>