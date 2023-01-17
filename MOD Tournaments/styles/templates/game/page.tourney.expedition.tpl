{block name="title" prepend}Tourney{/block}
{block name="content"}

<!--
 * @mods Tournoi
 * @version 1.0
 * @author yamilrh
 * @modification Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 -->

<div id="page">
	<div id="content">
<div id="achivment">
    <div class="ach_main_block">
    
        <div class="ach_head">
            <div class="ach_head_p">Tourney</div><!--/ach_head_p-->
            <div class="fright">
                
            </div><!--/ach_head_right-->
        </div><!--/ach_head-->

		<div class="ach_main_content">
                            
            <div class="ach_menu">
                <ul>
                	                    <li{if $touneyGroup == 1} class="enable"{/if}>
                        <a href="game.php?page=tourney&amp;group=1">Alpha</a>
                    </li>
                                        <li{if $touneyGroup == 2} class="enable"{/if}>
                        <a href="game.php?page=tourney&amp;group=2">Beta</a>
                    </li>
                                        <li{if $touneyGroup == 3} class="enable"{/if}>
                        <a href="game.php?page=tourney&amp;group=3">Gamma</a>
                    </li>
                                        <li{if $touneyGroup == 4} class="enable"{/if}>
                        <a href="game.php?page=tourney&amp;group=4">Delta</a>
                    </li>
                                        <li{if $touneyGroup == 5} class="enable"{/if}>
                        <a href="game.php?page=tourney&amp;group=5">Epsilon</a>
                    </li>
					<li class="enable">
                        <a href="game.php?page=tourney&mode=expedition" >Arsenal Event</a>
                    </li>
                                    </ul>  
            </div> 
            <div class="ach_content_box">
            	<div style="float:left; width:100%;">
                               
              
                               
                <div id="ach_level" class="ach_content">
                	
                                       
                                        
                	<div class="ach_img" style="
    width: 100%;
    margin: auto;
    float: none;margin-bottom: 10px;
">
                    	<img alt="Peaceful" src="styles/images/find-arsenal.jpg">
                    </div>
                  
  <div class="left_part" style="width: 33.3%;float: left;">
                 <div class="record_rows" style="padding: 0px">
				 
               <div class="record_img_utits" style="padding: 5px;">        
                	<img alt="" src="media/gamemedia/styles/images/0041-Medal-Gold-icon.png">
            </div>
           
					   <div class="record_name_utits" style="line-height: 40px;color: #a52d29;float: none;width: 135px;">100€<img src="styles/images/atm.gif" style="
    height: 10px;
    width: 10px;
    border-radius: 5px;
    vertical-align: baseline;
    margin-left: 4px;
">

            </div>
					
					 
					 
                        <div class="required_blocks">
						
						                                
                            </div>
                    </div>    </div>
<div class="left_part" style="width: 33.3%;float: left;">
                 <div class="record_rows" style="padding: 0px">
				 
               <div class="record_img_utits" style="padding: 5px;">        
                	<img alt="" src="media/gamemedia/styles/images/0040-Medal-Silver-icon.png">
            </div>
            <div class="record_name_utits" style="line-height: 40px;color: #a52d29;float: none;width: 135px;">90€<img src="styles/images/atm.gif" style="
    height: 10px;
    width: 10px;
    border-radius: 5px;
    vertical-align: baseline;
    margin-left: 4px;
">

            </div>
                        <div class="required_blocks">
						
						                                
                            </div>
                    </div>    </div>
<div class="left_part" style="width: 33.3%;float: left;">
                 <div class="record_rows" style="padding: 0px">
				 
               <div class="record_img_utits" style="padding: 5px;">        
                	<img alt="" src="media/gamemedia/styles/images/0039-Medal-Bronze-icon.png">
            </div>
            <div class="record_name_utits" style="line-height: 40px;color: #a52d29;float: none;width: 135px;">80€<img src="styles/images/atm.gif" style="
    height: 10px;
    width: 10px;
    border-radius: 5px;
    vertical-align: baseline;
    margin-left: 4px;
">

            </div>
                        <div class="required_blocks">
						
						                                
                            </div>
                    </div>    </div>

					
					
                                            </div> <!--/ach_content_text-->     
                    <div class="clear"></div>               
                </div> <!--/ach_content-->
        		
               
               
                                        
                <div id="ally_content" class="conteiner" style="width: 100%;background: none;border: none;">
            <table class="tablesorter ally_ranks" style="margin-bottom: 3px;margin-top: 2px;width: 500px;">
    <tbody>
    <tr>
        <td style="width: 15%;text-align: center;color: #5ca6aa;background: #091d2e;border-color: #091d2e;">Position</td>
        <td style="width: 35%;text-align: center;color: #5ca6aa;background: #091d2e;border-color: #091d2e;">Name</td>
		<td style="width: 50%;text-align: center;color: #5ca6aa;background: #091d2e;border-color: #091d2e;">Points</td>
		
		
		
    </tr>
	</tbody>
    </table>

{foreach $userArbre as $ID => $Player}<a target="#" class="ticket_row_linck">
        <span class="ticket_row_linck_id" style="width:10%;text-align: center;border-right:none">#{$Player@iteration}</span>
		        <span class="ticket_row_linck_subject" style="width:39%; text-align: center;color:#a9a9a9;">{$Player.playerName}</span>
                <span class="ticket_row_linck_status" style="width:45%; text-align: center;color:#a9a9a9;">{$Player.expePoints}</span>
				
                        <span class="clear"></span>
    </a>  {foreachelse}<a target="#" class="ticket_row_linck">No players participates in this tournement</a>{/foreach}
					 
	 		</div>
                                            </div> <!--/ach_content_text-->     
                    <div class="clear"></div>               
                </div>
                                            </div> <!--/ach_content_text-->     
                    <div class="clear"></div>               
                </div> <!--/ach_content-->
        		                </div>   
                <div style="padding-top:7px;"></div>
                <div class="clear"></div>   
                            
	 		</div>
			 <!--/ach_content_box-->
            
        </div><!--/ach_main_content-->
        
    </div><!--/ach_main_block-->
</div><!--/achivment-->    
</div>
</div>
    <div class="clear"></div>   
    </div>         
</div><!--/body-->
{/block}
{block name="script" append}
{/block}