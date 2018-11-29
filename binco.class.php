<?php
class binoco {
	public $n = false, $r = false, $pool = false;
	
	public function __construct($pool=false,$r=false){
		$this->log = [];
		$this->load($pool,$r);
	}

	public function __call($method,$args){
        if(isset($this->$method)){
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

	public function load($pool=false,$r=false){
		if(!$pool || !$r) return;
		$this->set('pool',$pool);
		$this->set('r',$r);
	}

	public function set($k,$v){
		$allowed = ['pool','r','n'];
		if(!in_array($k,$allowed)) return;

		$this->$k = $v;
		if($k=='pool') $this->n = count($v);
	}

	public function display($set=false,$glue=" :: ",$sprintf=false){
		if(!$set) return;
		$type = gettype($set);
		if(preg_match('/^integer|double|string$/',$type)) $set = [$set];
		elseif($type=='object') $set = (array) $set;
		echo implode($glue,$set)."\n";
	}

	public function checksum(){
		if(!$this->n || !$this->r) return false;
		$r = $this->r;
		$n = $this->n;

		if($r>$n || $r<0) return 0;
		if($r==0 || $r==$n) return 1;
		$v = 1;
		for($i=0; $i<$r; $i++)
		    $v = $v * ($n-$i) / ($r-$i);
		return $v;
	}

	public function matchlog($binary,$match,$checksum,$ms,$binlen){
		$progress = $match/$checksum*100;
		$duration = microtime(true)-$ms;
		$eta = (($duration*100) / $progress) - $duration;

		$progress = number_format($progress,6,'.','');
		$duration = number_format($duration,7,'.','');
		$eta = gmdate('H:i:s',$eta).substr(number_format(fmod($eta,1),5,'.',''),1);

		if($binlen<40){
			$this->display([
				'[bin] '.$binary,
				'[match] '.sprintf('%'.strlen($checksum).'s',$match).'/'.$checksum,
				'[progress] '.sprintf('%10s',$progress).'%',
				'[runtime] '.sprintf('%14s',$duration).'s',
				'[eta] '.$eta
			]);
		} else {
			$this->display([
				'[match] '.sprintf('%'.strlen($checksum).'s',$match).'/'.$checksum,
				'[progress] '.sprintf('%10s',$progress).'%',
				'[runtime] '.sprintf('%12s',$duration).'s',
				'[eta] '.$eta
			]);
		}
	}

	public function process($output=1,$callback=false){
		if(!$this->n || !$this->r || !$this->pool) return false;
		if(!$output) $output = 0;

		$ms = microtime(true);
		$r = $this->r;
		$n = $this->n;
		$checksum = $this->checksum();

		if($output==1){
			$this->display([
				'C('.$this->n.','.$this->r.') = '.number_format($checksum,0,'.',','),
				'total combinations possible'
			]);
		}

		$match = 0;
		$binary = str_pad(implode('',array_fill(0,$r,1)),$n,0,STR_PAD_RIGHT);
		while($binary){
			$match++;

			if($callback && isset($this->$callback)){
				$combo = [];
				foreach(str_split($binary) as $k=>$v)
					if($v=='1') $combo[] = $this->pool[$k];
				$this->$callback($combo);
			}

			if($output==1){
				$binlen = strlen($binary);
				//--- throttling output to improve performance
					if($binlen<40) $this->matchlog($binary,$match,$checksum,$ms,$binlen);
					else {
						$out = false;
						if($binlen<80){
							if($match%100000==0) $out = true;
						} elseif($binlen>=100){
							if($match%10000000==0) $out = true;
						} elseif($binlen>=80){
							if($match%1000000==0) $out = true;
						}
						if($out) $this->matchlog(false,$match,$checksum,$ms,$binlen);
					}
				//--- throttling output to improve performance
			}

			$next = $this->next($binary);
			if(!$next){
				$binary = false;
				break;
			}

			$binary = $next;
		}

		if($output>0){
			$this->display([
				'[complete]',
				number_format(microtime(true)-$ms,7,'.','').'s'
			]);
			echo "\n";
		}
	}

	public function next($binary=false){
		if(!$binary) return false;
		$debug = false;
		
		$binlen = strlen($binary);
		if($debug) echo $binary."\n";
		
		$i = strrpos($binary,'10');
		if($debug) echo $i."\n";
		
		if($i!==false){
			$i = substr($binary,0,$i).'01';
			if($debug) echo $i."\n";
		
			$add_on = $this->r - substr_count($i,'1');
			if($add_on>0) $i = $i.implode('',array_fill(0,$add_on,'1'));
			if(strlen($i)<$binlen) $i = str_pad($i,$binlen,'0');
			if($debug) $this->display(['new bin',$i]);
		}
		return $i;		
	}
}
?>