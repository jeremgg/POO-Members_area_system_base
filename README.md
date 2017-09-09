<h1>Create a user account system in PHP part 2</h1>

<br>
<br>
<br>


<p>
  Creation in php object oriented of a basic project regrouping the main functionalities of a member space system. This system makes it possible to have the basis of a similar project much more complex.
</p>

<br>

<p>
  This code follows the same project realized in procedural php which I adapted for the POO.
</p>

<br>

<p>
  I did not use the functions like the namespace or the MVC system to make the code as simple as possible, but obviously for a more complex project it will be necessary to modify it accordingly.<br>This project will soon be reorganized into MVC.
</p>

<br>
<br>

<h2>Database structure</h2>

<p>
  The table structure of the database is shown below but it is possible to rename the table and the fields according to the conventions of each.
</p>

<br>

<ul>
  <li>members</li>
  <ul>
    <li>id</li>
    <li>username</li>
    <li>email</li>
    <li>password</li>
    <li>confirmation_token</li>
    <li>confirmed_at</li>
    <li>reset_token</li>
    <li>reset_at</li>
    <li>remember_token</li>
  </ul>
</ul>
