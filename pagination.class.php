<?php
/*
 * @author: Airlangga bayu seto  - qu4ck@iso.web.id
 * iso.web.id software development
 */

class Pagination {
    var $anchor, $query, $lnk;

   /**
     *
     * @param String $sql query sql
     * @param Int $limit limit maximal per halaman
     * @param String $link link tujuan
     */

    function paging($sql,$limit='10') {
        global $db, $set;

        $mulai = (int)$_GET['a'];
        $hal = (int)$_GET['p'];
        $jml= $limit;

        $jmlrec = mysql_num_rows(mysql_query($sql));


        $jmlhal = intval($jmlrec/$jml);
        if ($jmlrec%$jml>0) {
            $jmlhal+=1;
        }

        $batasku = $jmlhal-1;

        if (!$hal) {
            $hal=1;
            $mulai=0;
        }
        $awal=$mulai;

        $sql  .= " LIMIT $awal, $jml";
        $this->query = $sql;

        //$this->anchor ="Page <b>$hal</b> of <b>$jmlhal</b> | ";
        if ($jmlhal>1) {
            if ($hal>1) {
                $hal--;
                $awal-=$jml;
                $this->anchor .="<a href=".$this->lnk."?a=$awal&p=".$hal." id=\"sebelumnya\"><font color=\"#000000\"><b>&laquo; previous</b></font></a>";
                if ($hal++<$batasku) {
                    $hal+=1;
                    $awal+=$jml;
                    $awal+=$jml;
                    $this->anchor .=" | <a href=".$this->lnk."?a=$awal&p=".$hal." id=\"selanjutnya\"><font color=\"#000000\" ><b>next &raquo;</b></font></a>";
                }
            } else {
                $hal++;
                $awal+=$jml;
                $this->anchor .="<a href=".$this->lnk."?a=$awal&p=".$hal." id=\"selanjutnya\"><font color=\"#000000\"><b>next &raquo;</b></font></a>";

            }
        }
    }
}
?>
