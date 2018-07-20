<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class textmining {
	function deleteInflectionSuffixes($kata) 
    {
        $kataAsal = $kata;
        if (preg_match('/([km]u|nya|[kl]ah|pun)$/i', $kata)) 
        {
            $__kata = preg_replace('/(nya|[kl]ah|pun)$/i', '', $kata);
            if (preg_match('/([klt]ah|pun)$/i', $kata))
            {
                if (preg_match('/([km]u|nya)$/i', $kata))
                {
                    $__kata__ = preg_replace('/([km]u|nya)$/i', '', $kata);
                    return $__kata__;
                }
            }
            return $__kata;
        }
        return $kataAsal;      
    }
    
    function deleteDerivationSuffixes($kata) 
    {
        $kataAsal = $kata;
        $CI =& get_instance();
        $CI->load->model('Textmining_Model');

        if (preg_match('/(i|an)$/i', $kata)) 
        {
            
            $__kata = preg_replace('/(i|an)$/i', '', $kata);
            if ($CI->Textmining_Model->cekKataDasar($__kata))
            {
                return $__kata;
            }
            
            
            if (preg_match('/(kan)$/i', $kata)) 
            {
                $__kata__ = preg_replace('/(kan)$/i', '', $kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
            if ($this->checkPrefixDisallowedSuffixes($kata)) 
            {
                return $kataAsal;
            }
        }
        return $kataAsal;
    }
    
    function deleteDerivationPrefixes($kata) 
    {
        $kataAsal = $kata;
        $CI =& get_instance();
        $CI->load->model('Textmining_Model');
        // Jika di-,ke-,se-
        if (preg_match('/^(di|[ks]e)/i', $kata)) 
        {
            $__kata = preg_replace('/^(di|[ks]e)/i', '', $kata);
            
            if ($CI->Textmining_Model->cekKataDasar($__kata)) 
            {
                return $__kata;
            }
            
            $__kata__ = $this->deleteDerivationSuffixes($__kata);
            if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
            {
                return $__kata__;
            }
            
            if (preg_match('/^(diper)/i', $kata)) 
            {
                $__kata = preg_replace('/^(diper)/i', '', $kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                {
                    return $__kata;
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
                
                $__kata = preg_replace('/^(diper)/i', 'r', $kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
        }
        
        if (preg_match('/^([tmbp]e)/i', $kata)) 
        { 
            
            if (preg_match('/^(te)/i', $kata)) 
            { 
                if (preg_match('/^(terr)/i', $kata)) 
                {
                    return $kata;
                }
                
                if (preg_match('/^(ter)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor]er[aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor]er[^aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor][^(er)])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(te[^aiueor]er[aiueo])/i', $kata)) 
                {
                    return $kata;
                }
                
                if (preg_match('/^(te[^aiueor]er[^aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(te)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }
            
            if (preg_match('/^(me)/i', $kata)) 
            {
                if (preg_match('/^(meng)[aiueokghq]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(meng)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(meng)/i', 'k', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(meny)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(meny)/i', 's', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(mem)[bfpv]/i', $kata)) 
                { // 3.
                    $__kata = preg_replace('/^(mem)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mempek)/i', 'k', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(men)[cdjsz]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(men)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(me)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(me)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(men)/i', 't', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(be)/i', $kata)) 
            {
                if (preg_match('/^(ber)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ber)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata = preg_replace('/^(ber)/i', 'r', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/(ber)[^aiueo]/i', $kata)) 
                { // 2.
                    $__kata = preg_replace('/(ber)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) {
                        return $__kata__;
                    }
                }
                if (preg_match('/^(be)[k]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(be)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }
            
            if (preg_match('/^(pe)/i', $kata)) 
            {
                if (preg_match('/^(peng)[aiueokghq]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(peng)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(peny)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(peny)/i', 's', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pem)[bfpv]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pem)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pen)[cdjsz]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pen)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(pem)/i', 'p', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                }

                if (preg_match('/^(pen)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pen)/i', 't', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(per)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(per)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(per)/i', 'r', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pe)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pe)/i', '', $kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(memper)/i', $kata)) 
            {
                $__kata = preg_replace('/^(memper)/i', '', $kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
                
                $__kata = preg_replace('/^(memper)/i', 'r', $kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($CI->Textmining_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
        }
        
        /* --- Cek Ada Tidaknya Prefik/Awalan ------ */
        if (preg_match('/^(di|[kstbmp]e)/i', $kata) == FALSE) 
        {
            return $kataAsal;
        }
        
    }
    
    function checkPrefixDisallowedSuffixes($kata) 
    {
        // be- dan -i
        if (preg_match('/^(be)[[:alpha:]]+(i)$/i', $kata)) 
        {
            return true;
        }
        
        // di- dan -an
        if (preg_match('/^(di)[[:alpha:]]+(an)$/i', $kata)) 
        {
            return true;
        }
        
        // ke- dan -i,-kan
        if (preg_match('/^(ke)[[:alpha:]]+(i|kan)$/i', $kata)) 
        {
            return true;
        }
        
        // me- dan -an
        if (preg_match('/^(me)[[:alpha:]]+(an)$/i', $kata)) 
        {
            return true;
        }
        
        // se- dan -i,-kan
        if (preg_match('/^(se)[[:alpha:]]+(i|kan)$/i', $kata)) 
        {
            return true;
        }
        
        return FALSE;
    }

    function optimize_code()
    {
        set_time_limit(86400);
        ini_set('memory_limit', '-1');
    }
}