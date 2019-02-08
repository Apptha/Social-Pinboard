
window.onscroll = function()
{
    
    var scrollpos = getScrollingPosition();
    if(scrollpos[1]>44)
    {
        if(document.getElementById('Nag'))
        document.getElementById('Nag').className = 'Nag fixed';
     if(document.getElementById('CategoriesBar'))
        document.getElementById('CategoriesBar').className = 'fixed';
        
    }
    else
    {
         if(document.getElementById('Nag'))
        document.getElementById('Nag').className = 'Nag';
     if(document.getElementById('CategoriesBar'))
        document.getElementById('CategoriesBar').className = '';


    }
};
			 function getScrollingPosition()
{
    var position = [0, 0];
    if (typeof window.pageYOffset != 'undefined')
    {
        position = [
            window.pageXOffset,
            window.pageYOffset
        ];
    }
    else if (typeof document.documentElement.scrollTop
        != 'undefined' && document.documentElement.scrollTop > 0)
    {
        position = [
            document.documentElement.scrollLeft,
            document.documentElement.scrollTop
        ];
    }
    else if (typeof document.body.scrollTop != 'undefined')
    {
        position = [
            document.body.scrollLeft,
            document.body.scrollTop
        ];
    }
    return position;
}

		