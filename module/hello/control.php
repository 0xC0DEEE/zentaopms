<?php 
class hello extends control
{
    public function world()
    {
        $this->view->helloworld =  $this->hello->world();
        $this->view->link = $this->createLink('hello', 'world');
        $this->display();
    }
}
?>