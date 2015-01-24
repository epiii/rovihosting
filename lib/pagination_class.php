<?php
    class Pagination_class{
        var $result;
        var $anchors;
        var $total;

        function Pagination_class($mnu,$qry,$starting,$recpage){
            $rst        =   mysql_query($qry) or die(mysql_error());
            $numrows    =   mysql_num_rows($rst);
            $qry        .= " limit $starting, $recpage";
            $this->result=   mysql_query($qry) or die(mysql_error());
            $next       =   $starting+$recpage;
            $var        =   ((intval($numrows/$recpage))-1)*$recpage;
            $page_showing=   intval($starting/$recpage)+1;
            $total_page =   ceil($numrows/$recpage);
    		// var_dump($recpage);exit();
     
            if($numrows % $recpage != 0){
                $last = ((intval($numrows/$recpage)))*$recpage;
            }else{
                $last = ((intval($numrows/$recpage))-1)*$recpage;
            }
            $previous = $starting-$recpage;
            // var_dump($previous);exit();
            // $anc = "<ul id='pagination' >";
            // $anc = '<ul class="pagination" id="pagination" >';
            $anc = '<ul id="pagination" class="pagination pagination-lg">';
            if($previous < 0){ //gak aktif
                $anc .= '<li class="previous-disabled"><i class="icon-fast-backward"></i></li>';
                $anc .= '<li class="previous-disabled"><i class="icon-step-backward"></i></li>';
                // $anc .= "<li class='previous-off'>First</li>";
                // $anc .= "<li class='previous-off'>Previous</li>";
            }else{ //aktif
                $anc .= '<li pg=0 class="previous">
                            <a href="javascript:pagination(0,\'tampil\',\''.$mnu.'\');">
                                <i class="icon-fast-backward"></i>
                            </a>
                        </li>';
        		$anc .= '<li pg="'.$previous.'" class="previous"> 
                            <a href="javascript:pagination('.$previous.',\'tampil\',\''.$mnu.'\');">
                                <i class="icon-step-backward"></i>
                            </a>
                        </li>';
    		}
     
            ################If you dont want the numbers just comment this block###############
            $norepeat = 2;//no of pages showing in the left and right side of the current page in the anchors
            $j = 1;
            $anch = "";
            for($i=$page_showing; $i>1; $i--)
    		{
                $fpreviousPage = $i-1;
                $page = ceil($fpreviousPage*$recpage)-$recpage;
                $anch = '<li class="prevnext" pg="'.$page.'"><a href="javascript:pagination(\''.$page.'\',\'tampil\', \''.$mnu.'\');">'.$fpreviousPage.'</a></li>'.$anch;
                if($j == $norepeat) break;
                $j++;
            }
            $anc .= $anch;
            $anc .='<li class="active">'.$page_showing.'</li>';
            $j = 1;
            for($i=$page_showing; $i<$total_page; $i++)
    		{
                $fnextPage = $i+1;
                $page = ceil($fnextPage*$recpage)-$recpage;
                $anc .= '<li class="prevnext" pg="'.$page.'"><a href="javascript:pagination('.$page.',\'tampil\',\''.$mnu.'\');">'.$fnextPage.'</a></li>';
                if($j==$norepeat) 
    				break;
                $j++;
            }
            ############################################################
            if($next >= $numrows)
    		{
                //$anc .= "<li class='previous-off'>Next</li>";
                //$anc .= "<li class='previous-off'>Last</li>";
                $anc .= '<li class="next-disabled"><i class="icon-step-forward"></i></li>';
                $anc .= '<li class="next-disabled"><i class="icon-fast-forward"></i></li>';
            }
    		else
    		{
                $anc .= '<li class="next" pg='.$next.'><a href="javascript:pagination('.$next.',\'tampil\',\''.$mnu.'\');"><i class="icon-step-forward"></i></a></li>';
    			$anc .= '<li class="next" pg='.$last.'><a href="javascript:pagination('.$last.',\'tampil\',\''.$mnu.'\');"><i class="icon-fast-forward"></i></a></li>';
            }
            $anc .= '</ul>';

            $this->anchors = $anc;
            $this->total ='Halaman ke: <b>'.$page_showing.'</b> dari <b>'.$total_page.'</b> (Total Data: <b>'.$numrows.'</b>)';
        }
    }
?>