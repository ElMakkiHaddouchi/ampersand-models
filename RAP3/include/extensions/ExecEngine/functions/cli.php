<?php

use Ampersand\Log\Logger;
use Ampersand\Config;
use Ampersand\Core\Relation;
use Ampersand\Core\Atom;
use Ampersand\Core\Concept;
use Ampersand\Session;

// Ampersand commando's mogen niet in dit bestand worden aangepast. 
// De manier om je eigen commando's te regelen is door onderstaande regels naar jouw localSettings.php te copieren en te veranderen
// Nu kan dat nog niet, omdat zulke strings niet de paden e.d. kunnen doorgeven.
// Config::set('proto', 'RAP3', 'value');
// Config::set('proto', 'RAP3', 'value');
// Config::set('proto', 'RAP3', 'value');
// Config::set('proto', 'RAP3', 'value');
// Config::set('proto', 'RAP3', 'value');


// Required Ampersand script
/*
RELATION loadedInRAP3[Script*Script] [PROP] 



*/


function CompileWithAmpersand($action, $filePath, $scriptAtomId){
    Logger::getLogger('EXECENGINE')->info("CompileWithAmpersand({$action}, {$filePath}, {$scriptAtomId})");
    
    $path = realpath(Config::get('absolutePath') . $filePath);
    $scriptConcept = Concept::getConceptByLabel("Script");
    $scriptAtom = new Atom($scriptAtomId,$scriptConcept);
    $dir = "scripts/{$scriptAtom->id}";

    switch($action){
        case 'compilecheck' : 
            CompileCheck($path, $scriptAtom);
            break;
        case 'diagnosis':
            Diagnosis($path, $scriptAtom, $dir);
            break;
        case 'loadPopInRAP3' : 
            loadPopInRAP3($path, $scriptAtom, $dir);
            break;
        case 'fspec' : 
            FuncSpec($path, $scriptAtom, $dir.'/fSpec');
            break;
        case 'prototype' : 
            Prototype($path, $scriptAtom, $dir);
            break;
        default :
            Logger::getLogger('EXECENGINE')->error("Unknown action ({$action}) specified");
            break;
    }
}

function CompileCheck($path, $scriptAtom){
    $default = "Ampersand {$path}";
    $cmd = is_null(Config::get('CompileCheckCmd', 'RAP3')) ? $default : Config::get('CompileCheckCmd', 'RAP3');
    Logger::getLogger('COMPILEENGINE')->debug("cmd:'{$cmd}'");
    
    // Execute cmd, and populate 'scriptOk' upon success
    Execute($cmd, $response, $exitcode, 'scriptOk', $scriptAtom);
    Logger::getLogger('COMPILEENGINE')->debug("exitcode:'{$exitcode}'");
    
    // Add response to database
    $conceptCompileResponse = Concept::getConceptByLabel("CompileResponse");
    $relCompileResponse = Relation::getRelation('compileresponse',$scriptAtom->concept,$conceptCompileResponse);
    $responseAtom = new Atom($response,$conceptCompileResponse);
    $relCompileResponse->addLink($scriptAtom,$responseAtom,false,'COMPILEENGINE');
    
}

function FuncSpec($path, $scriptAtom, $relDir){
    
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = pathinfo($path, PATHINFO_FILENAME);
    $outputDir = Config::get('absolutePath').$relDir;
    $default = "Ampersand {$path} -fl --language=NL --outputDir=\"{$outputDir}\" --verbose";
    $cmd = is_null(Config::get('FuncSpecCmd', 'RAP3')) ? $default : Config::get('FuncSpecCmd', 'RAP3');
    Logger::getLogger('COMPILEENGINE')->debug("cmd:'{$cmd}'");

    // Execute cmd, and populate 'funcSpecOk' upon success
    Execute($cmd, $response, $exitcode, 'funcSpecOk', $scriptAtom);

    // Create FileObject in database
    $fileObjectAtom = Concept::getConceptByLabel('FileObject')->createNewAtom();
    $conceptFilePath = Concept::getConceptByLabel('FilePath');
    Relation::getRelation('filePath[FileObject*FilePath]')->addLink($fileObjectAtom, new Atom("{$relDir}/{$filename}.pdf", $conceptFilePath));
    Relation::getRelation('originalFileName[FileObject*FileName]')->addLink($fileObjectAtom, new Atom('Functional specification', Concept::getConceptByLabel('FileName')));

    // Link fSpecAtom to scriptAtom
    Relation::getRelation('funcSpec[Script*FileObject]')->addLink($scriptAtom,$fileObjectAtom,false,'COMPILEENGINE');
    
}

function Diagnosis($path, $scriptAtom, $outputDir){
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = pathinfo($path, PATHINFO_FILENAME);
    
    $default = "Ampersand {$path} --diagnosis --language=NL --outputDir=\"{$outputDir}/diag\" --verbose";
    $cmd = is_null(Config::get('DiagCmd', 'RAP3')) ? $default : Config::get('DiagCmd', 'RAP3');
    Logger::getLogger('COMPILEENGINE')->debug("cmd:'{$cmd}'");

    // Execute cmd, and populate 'diagOk' upon success
    Execute($cmd, $response, $exitcode, 'diagOk', $scriptAtom);
    
    // Create FileObject in database
    $concept = Concept::getConcept('FileObject');
    $fileObjectAtom = $concept->createNewAtom();
    Relation::getRelation('filePath','FileObject','FilePath')->addLink($fileObjectAtom, new Atom("uploads/diag/{$filename}.pdf", 'FilePath'));
    Relation::getRelation('originalFileName','FileObject','FileName')->addLink($fileObjectAtom, new Atom('Diagnosis', 'FileName'));

    // Link diagAtom to scriptAtom
    Relation::getRelation('diag','Script','FileObject')->addLink($scriptAtom,$fileObjectAtom,false,'COMPILEENGINE');
    
}

function Prototype($path, $scriptAtom, $outputDir){
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = pathinfo($path, PATHINFO_FILENAME);
    
    $default = "Ampersand {$path} --proto=\"{$outputDir}/prototype\" --language=NL --verbose";
    $cmd = is_null(Config::get('ProtoCmd', 'RAP3')) ? $default : Config::get('ProtoCmd', 'RAP3');
    Logger::getLogger('COMPILEENGINE')->debug("cmd:'{$cmd}'");

    // Execute cmd, and populate 'protoOk' upon success
    Execute($cmd, $response, $exitcode, 'protoOk', $scriptAtom);
    
    // Link urlAtom to scriptAtom
    $urlAtom = new Atom ('hier de url', 'URL');
    Relation::getRelation('proto url relation name','Script','URL')->addLink($scriptAtom, $urlAtom, false,'COMPILEENGINE');
}

function loadPopInRAP3($path, $scriptAtom, $outputDir){
    $session = Session::singleton();
    
    $cmd = "Ampersand {$path} --proto=\"{$outputDir}/prototype\" --language=NL --verbose --gen-as-rap-model"; // het draait om: ../prototype/generics/metaPopulation.json
    Logger::getLogger('COMPILEENGINE')->debug("cmd:'{$cmd}'");
    
    // Execute cmd, and populate 'loadedInRAP3' upon success
    Execute($cmd, $response, $exitcode, 'loadedInRAP3', $scriptAtom);
    
    // Open and decode generated metaPopulation.json file
    $pop = file_get_contents("{$outputDir}/prototype/generics/metaPopulation.json");
    $pop = json_decode($pop, true);
    
    // Add atoms to database    
    foreach($pop['atoms'] as $atomPop){
        $concept = Concept::getConceptByLabel($atomPop['concept']);
        foreach($atomPop['atoms'] as $atomId){
            getRAPAtom($atomId, $concept)->addAtom(); // Add to database
        }
    }
    
    // Add links to database
    foreach($pop['links'] as $linkPop){
        $relation = Relation::getRelation($linkPop['relation']);
        foreach($linkPop['links'] as $link){
            $src = getRAPAtom($link['src'], $relation->srcConcept);
            $tgt = getRAPAtom($link['tgt'], $relation->tgtConcept);
            $relation->addLink($src, $tgt); // Add link to database
        }
    }
}

function getRAPAtom($atomId, $concept){
    static $arr = []; // initialize array in first call
    
    switch($concept->name){
        case 'Context' : // Context atoms get a new unique identifier
        
            // If atom is already changed earlier, use new id from cache
            if(isset($arr[$concept->name]) && array_key_exists($atomId, $arr[$concept->name])){
                $atom = new Atom($arr[$concept->name][$atomId], $concept);
            
            // Else create new id and store in cache
            }else{
                $atom = $concept->createNewAtom(); // Create new atom (with generated id)
                $arr[$concept->name][$atomId] = $atom->id; // Cache change
            }
            break;
        
        default : // All other atoms are left untouched
            $atom = new Atom($atomId, $concept);
            break;
    }
        
    return $atom;
}

/**
 * @param string $cmd command that needs to be executed
 * @param string &$response reference to textual output of executed command
 * @param int &$exitcode reference to exitcode of executed command
 * @param string $proprelname name of ampersand property relation that must be (de)populated upon success/failure
 * @param Atom $scriptAtom ampersand Script atom used for populating
 */
function Execute($cmd, &$response, &$exitcode, $proprelname, $scriptAtom){
    $output = array();
    exec($cmd, $output, $exitcode);

    // Set property '$proprelname[Script*Script] [PROP]' depending on the exit code
    $proprel = Relation::getRelation($proprelname,$scriptAtom->concept,$scriptAtom->concept);
    $proprel->addLink($scriptAtom,$scriptAtom,false,'COMPILEENGINE');
    // Before deleteLink always addLink (otherwise Exception will be thrown when link does not exist)
    if($exitcode != 0) $proprel->deleteLink($scriptAtom,$scriptAtom,false,'COMPILEENGINE');
    

    // format execution output
    $response = implode('\n',$output);
    Logger::getLogger('COMPILEENGINE')->debug("response:'{$response}'");
}

?>