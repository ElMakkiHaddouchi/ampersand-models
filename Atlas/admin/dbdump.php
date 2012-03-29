
      <?php
      require "Generics.php";
      require "php/DatabaseUtils.php";
      $dumpfile = fopen("dbdump.adl","w");
      fwrite($dumpfile, "CONTEXT RAP\n");
      fwrite($dumpfile, dumprel("inios[Concept*AtomID]","SELECT DISTINCT `Concept`, `AtomID` FROM `inios` WHERE `Concept` IS NOT NULL AND `AtomID` IS NOT NULL"));
      fwrite($dumpfile, dumprel("inipopu[Declaration*PairID]","SELECT DISTINCT `Declaration`, `PairID` FROM `inipopu` WHERE `Declaration` IS NOT NULL AND `PairID` IS NOT NULL"));
      fwrite($dumpfile, dumprel("compilererror[File*ErrorMessage]","SELECT DISTINCT `File`, `compilererror` FROM `File` WHERE `File` IS NOT NULL AND `compilererror` IS NOT NULL"));
      fwrite($dumpfile, dumprel("imageurl[Image*URL]","SELECT DISTINCT `Image`, `URL` FROM `imageurl` WHERE `Image` IS NOT NULL AND `URL` IS NOT NULL"));
      fwrite($dumpfile, dumprel("filename[File*FileName]","SELECT DISTINCT `File`, `filename` FROM `File` WHERE `File` IS NOT NULL AND `filename` IS NOT NULL"));
      fwrite($dumpfile, dumprel("filepath[File*FilePath]","SELECT DISTINCT `File`, `filepath` FROM `File` WHERE `File` IS NOT NULL AND `filepath` IS NOT NULL"));
      fwrite($dumpfile, dumprel("uploaded[User*File]","SELECT DISTINCT `User`, `File` FROM `uploaded` WHERE `User` IS NOT NULL AND `File` IS NOT NULL"));
      fwrite($dumpfile, dumprel("sourcefile[Context*AdlFile]","SELECT DISTINCT `ctxnm`, `sourcefile` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `sourcefile` IS NOT NULL"));
      fwrite($dumpfile, dumprel("includes[Context*File]","SELECT DISTINCT `Context`, `File` FROM `includes` WHERE `Context` IS NOT NULL AND `File` IS NOT NULL"));
      fwrite($dumpfile, dumprel("applyto[G*AdlFile]","SELECT DISTINCT `G`, `applyto` FROM `G` WHERE `G` IS NOT NULL AND `applyto` IS NOT NULL"));
      fwrite($dumpfile, dumprel("functionname[G*String]","SELECT DISTINCT `G`, `functionname` FROM `G` WHERE `G` IS NOT NULL AND `functionname` IS NOT NULL"));
      fwrite($dumpfile, dumprel("operation[G*Int]","SELECT DISTINCT `G`, `operation` FROM `G` WHERE `G` IS NOT NULL AND `operation` IS NOT NULL"));
      fwrite($dumpfile, dumprel("newfile[User*NewAdlFile]","SELECT DISTINCT `User`, `newfile` FROM `User` WHERE `User` IS NOT NULL AND `newfile` IS NOT NULL"));
      fwrite($dumpfile, dumprel("savepopulation[Context*SavePopFile]","SELECT DISTINCT `ctxnm`, `savepopulation` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `savepopulation` IS NOT NULL"));
      fwrite($dumpfile, dumprel("savecontext[Context*SaveAdlFile]","SELECT DISTINCT `ctxnm`, `savecontext` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `savecontext` IS NOT NULL"));
      fwrite($dumpfile, dumprel("countrules[Context*Int]","SELECT DISTINCT `ctxnm`, `countrules` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `countrules` IS NOT NULL"));
      fwrite($dumpfile, dumprel("countdecls[Context*Int]","SELECT DISTINCT `ctxnm`, `countdecls` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `countdecls` IS NOT NULL"));
      fwrite($dumpfile, dumprel("countcpts[Context*Int]","SELECT DISTINCT `ctxnm`, `countcpts` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `countcpts` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptpic[Pattern*Image]","SELECT DISTINCT `ptnm`, `ptpic` FROM `Conid` WHERE `ptnm` IS NOT NULL AND `ptpic` IS NOT NULL"));
      fwrite($dumpfile, dumprel("cptpic[Concept*Image]","SELECT DISTINCT `cptnm`, `cptpic` FROM `Conid` WHERE `cptnm` IS NOT NULL AND `cptpic` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrpic[Rule*Image]","SELECT DISTINCT `rrnm`, `rrpic` FROM `ADLid` WHERE `rrnm` IS NOT NULL AND `rrpic` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrviols[Rule*Violation]","SELECT DISTINCT `Rule`, `Violation` FROM `rrviols` WHERE `Rule` IS NOT NULL AND `Violation` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ctxnm[Context*Conid]","SELECT DISTINCT `ctxnm`, `Conid` FROM `Conid` WHERE `ctxnm` IS NOT NULL AND `Conid` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ctxpats[Context*Pattern]","SELECT DISTINCT `Context`, `Pattern` FROM `ctxpats` WHERE `Context` IS NOT NULL AND `Pattern` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ctxcs[Context*Concept]","SELECT DISTINCT `Context`, `Concept` FROM `ctxcs` WHERE `Context` IS NOT NULL AND `Concept` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptnm[Pattern*Conid]","SELECT DISTINCT `ptnm`, `Conid` FROM `Conid` WHERE `ptnm` IS NOT NULL AND `Conid` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptrls[Pattern*Rule]","SELECT DISTINCT `Pattern`, `Rule` FROM `ptrls` WHERE `Pattern` IS NOT NULL AND `Rule` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptgns[Pattern*Gen]","SELECT DISTINCT `Pattern`, `Gen` FROM `ptgns` WHERE `Pattern` IS NOT NULL AND `Gen` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptdcs[Pattern*Declaration]","SELECT DISTINCT `Pattern`, `Declaration` FROM `ptdcs` WHERE `Pattern` IS NOT NULL AND `Declaration` IS NOT NULL"));
      fwrite($dumpfile, dumprel("ptxps[Pattern*Blob]","SELECT DISTINCT `Pattern`, `Blob` FROM `ptxps` WHERE `Pattern` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("gengen[Gen*Concept]","SELECT DISTINCT `Gen`, `gengen` FROM `Gen` WHERE `Gen` IS NOT NULL AND `gengen` IS NOT NULL"));
      fwrite($dumpfile, dumprel("genspc[Gen*Concept]","SELECT DISTINCT `Gen`, `genspc` FROM `Gen` WHERE `Gen` IS NOT NULL AND `genspc` IS NOT NULL"));
      fwrite($dumpfile, dumprel("cptnm[Concept*Conid]","SELECT DISTINCT `cptnm`, `Conid` FROM `Conid` WHERE `cptnm` IS NOT NULL AND `Conid` IS NOT NULL"));
      fwrite($dumpfile, dumprel("cptos[Concept*AtomID]","SELECT DISTINCT `cptos`, `AtomID` FROM `AtomID` WHERE `cptos` IS NOT NULL AND `AtomID` IS NOT NULL"));
      fwrite($dumpfile, dumprel("cptdf[Concept*Blob]","SELECT DISTINCT `Concept`, `Blob` FROM `cptdf` WHERE `Concept` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("cptpurpose[Concept*Blob]","SELECT DISTINCT `Concept`, `Blob` FROM `cptpurpose` WHERE `Concept` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("atomvalue[AtomID*Atom]","SELECT DISTINCT `AtomID`, `atomvalue` FROM `AtomID` WHERE `AtomID` IS NOT NULL AND `atomvalue` IS NOT NULL"));
      fwrite($dumpfile, dumprel("src[Sign*Concept]","SELECT DISTINCT `Sign`, `src` FROM `Sign` WHERE `Sign` IS NOT NULL AND `src` IS NOT NULL"));
      fwrite($dumpfile, dumprel("trg[Sign*Concept]","SELECT DISTINCT `Sign`, `trg` FROM `Sign` WHERE `Sign` IS NOT NULL AND `trg` IS NOT NULL"));
      fwrite($dumpfile, dumprel("pairvalue[PairID*Pair]","SELECT DISTINCT `PairID`, `pairvalue` FROM `PairID` WHERE `PairID` IS NOT NULL AND `pairvalue` IS NOT NULL"));
      fwrite($dumpfile, dumprel("left[Pair*AtomID]","SELECT DISTINCT `Pair`, `left` FROM `Pair` WHERE `Pair` IS NOT NULL AND `left` IS NOT NULL"));
      fwrite($dumpfile, dumprel("right[Pair*AtomID]","SELECT DISTINCT `Pair`, `right` FROM `Pair` WHERE `Pair` IS NOT NULL AND `right` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decnm[Declaration*Varid]","SELECT DISTINCT `Declaration`, `decnm` FROM `Declaration` WHERE `Declaration` IS NOT NULL AND `decnm` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decsgn[Declaration*Sign]","SELECT DISTINCT `Declaration`, `decsgn` FROM `Declaration` WHERE `Declaration` IS NOT NULL AND `decsgn` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decprps[Declaration*PropertyRule]","SELECT DISTINCT `decprps`, `PropertyRule` FROM `ADLid` WHERE `decprps` IS NOT NULL AND `PropertyRule` IS NOT NULL"));
      fwrite($dumpfile, dumprel("declaredthrough[PropertyRule*Property]","SELECT DISTINCT `PropertyRule`, `Property` FROM `declaredthrough` WHERE `PropertyRule` IS NOT NULL AND `Property` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decprL[Declaration*String]","SELECT DISTINCT `Declaration`, `decprL` FROM `Declaration` WHERE `Declaration` IS NOT NULL AND `decprL` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decprM[Declaration*String]","SELECT DISTINCT `Declaration`, `decprM` FROM `Declaration` WHERE `Declaration` IS NOT NULL AND `decprM` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decprR[Declaration*String]","SELECT DISTINCT `Declaration`, `decprR` FROM `Declaration` WHERE `Declaration` IS NOT NULL AND `decprR` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decmean[Declaration*Blob]","SELECT DISTINCT `Declaration`, `Blob` FROM `decmean` WHERE `Declaration` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decpurpose[Declaration*Blob]","SELECT DISTINCT `Declaration`, `Blob` FROM `decpurpose` WHERE `Declaration` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("decpopu[Declaration*PairID]","SELECT DISTINCT `Declaration`, `PairID` FROM `decpopu` WHERE `Declaration` IS NOT NULL AND `PairID` IS NOT NULL"));
      fwrite($dumpfile, dumprel("exprvalue[ExpressionID*Expression]","SELECT DISTINCT `ExpressionID`, `exprvalue` FROM `ExpressionID` WHERE `ExpressionID` IS NOT NULL AND `exprvalue` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rels[ExpressionID*Relation]","SELECT DISTINCT `ExpressionID`, `Relation` FROM `rels` WHERE `ExpressionID` IS NOT NULL AND `Relation` IS NOT NULL"));
      fwrite($dumpfile, dumprel("relnm[Relation*Varid]","SELECT DISTINCT `Relation`, `relnm` FROM `Relation` WHERE `Relation` IS NOT NULL AND `relnm` IS NOT NULL"));
      fwrite($dumpfile, dumprel("relsgn[Relation*Sign]","SELECT DISTINCT `Relation`, `relsgn` FROM `Relation` WHERE `Relation` IS NOT NULL AND `relsgn` IS NOT NULL"));
      fwrite($dumpfile, dumprel("reldcl[Relation*Declaration]","SELECT DISTINCT `Relation`, `reldcl` FROM `Relation` WHERE `Relation` IS NOT NULL AND `reldcl` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrnm[Rule*ADLid]","SELECT DISTINCT `rrnm`, `ADLid` FROM `ADLid` WHERE `rrnm` IS NOT NULL AND `ADLid` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrexp[Rule*ExpressionID]","SELECT DISTINCT `rrnm`, `rrexp` FROM `ADLid` WHERE `rrnm` IS NOT NULL AND `rrexp` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrmean[Rule*Blob]","SELECT DISTINCT `Rule`, `Blob` FROM `rrmean` WHERE `Rule` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, dumprel("rrpurpose[Rule*Blob]","SELECT DISTINCT `Rule`, `Blob` FROM `rrpurpose` WHERE `Rule` IS NOT NULL AND `Blob` IS NOT NULL"));
      fwrite($dumpfile, "ENDCONTEXT");
      fclose($dumpfile);
      
      function dumprel ($rel,$quer)
      {
        $rows = DB_doquer($quer);
        $pop = "";
        foreach ($rows as $row)
          $pop = $pop.";(\"".escapedoublequotes($row[0])."\",\"".escapedoublequotes($row[1])."\")\n  ";
        return "POPULATION ".$rel." CONTAINS\n  [".substr($pop,1)."]\n";
      }
      function escapedoublequotes($str) { return str_replace("\"","\\\"",$str); }
      ?>