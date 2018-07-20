<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Research_model extends MY_Model
{
	function __construct() 
    {
        parent::__construct();
        $this->katadasar = 'word_dictionary';
        $this->stoplist = 'stoplist';
    }

    function get_aggregation_id($kata)
    {
        $this->db->select('aggregation_id');
        $this->db->where_in('aggregation_word', $kata);
        $query = $this->db->get('aggregation');

        $array = $query->result_array();
        for($i = 0; $i<count($array); $i++)
        {
            $result[$i] = $array[$i]['aggregation_id'];
        }
        return $result;
    }

    function get_data_score_byIntervalDate($kata, $portal, $from, $until, $cat)
    {
        $from = date("Y-m-d", strtotime($from));
        $until = date("Y-m-d", strtotime($until));
        $portal = implode(',', $portal);
        if($cat == 0)
        {
            for($i = 0; $i < count($kata); $i++)
            {
                $kata[$i] = "'".$kata[$i]."'";
            }
            $kata = implode(', ', $kata);
            $query = $this->db->query('select * from aggregation_value av join aggregation ag on av.aggregation_id = ag.aggregation_id join document doc on doc.document_id = av.document_id join portals por on doc.portal_id = por.portal_id join document_links links on doc.link_id = links.link_id where doc.date_published BETWEEN "'.$from.'" AND "'.$until.'" AND ag.aggregation_word IN ('.$kata.') AND doc.portal_id IN ('.$portal.') order by doc.date_published;');
        }
        else
        {
            $this->db->select('*');
            $this->db->from('document doc');
            $this->db->join('portals por', 'por.portal_id = doc.portal_id');
            $this->db->join('document_links lks', 'doc.link_id = lks.link_id');
            $this->db->where('doc.date_published >=', $from);
            $this->db->where('doc.date_published <=', $until);
            $this->db->where_in('doc.portal_id', $portal);
            for($i = 0; $i < count($kata); $i++)
            {
                if($i == 0)
                    $this->db->like('doc.kataPenting', '"'.$kata[$i].'"');
                else
                    $this->db->or_like('doc.kataPenting', '"'.$kata[$i].'"');
            }
            
            $query = $this->db->get();
            // print_r($this->db->last_query());
        }
        return $query->result_array();
    }

    function get_chart_value_byIntervalDate($full_data, $sentiment)
    {
        $i = 0;
        foreach($full_data as $dt)
        {
            $data[$i]['date_published'] = $dt['date_published'];
            $data[$i]['c_value'] = $dt[$sentiment];
            $i++;
        }

        $data_result = array();
        foreach($data as $value)
        {
            $date_published = $value['date_published'];
            $c_value = $value['c_value'];

            $found = false;
            foreach($data_result as $key => $res)
            {
                if($res['date_published'] == $date_published)
                {
                    $data_result[$key]['c_value'] += $c_value;
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                array_push($data_result, $value);
            }
        }
        return $data_result;
    }

    function get_chart_value_byYear($portal, $interval, $kata, $year, $month, $sentiment)
    {
        //array dijadikan string untuk where in
        $portal = implode(',', $portal);
        for($i = 0; $i < count($kata); $i++)
        {
            $kata[$i] = "'".$kata[$i]."'";
        }
        $kata = implode(', ', $kata);

        $query = $this->db->query('select sum('.$sentiment.') as score from aggregation_value av join aggregation ag on av.aggregation_id = ag.aggregation_id join document doc on doc.document_id = av.document_id where year(doc.date_published) = '.$year.' AND month(doc.date_published) = '.$month.' AND ag.aggregation_word IN ('.$kata.') AND doc.portal_id IN ('.$portal.');');
        
        if(is_null($query->row('score')))
            return 0;
        return $query->row('score');
    }
    function get_data_score_byYear($portal, $kata, $year)
    {
        $this->db->select('*');
        $this->db->from('document doc');
        $this->db->join('portals por', 'por.portal_id = doc.portal_id');
        $this->db->join('document_links lks', 'doc.link_id = lks.link_id');
        $this->db->where('year(doc.date_published)', $year);
        $this->db->where_in('doc.portal_id', $portal);
        for($i = 0; $i < count($kata); $i++)
        {
            if($i == 0)
                $this->db->like('doc.kataPenting', '"'.$kata[$i].'"');
            else
                $this->db->or_like('doc.kataPenting', '"'.$kata[$i].'"');
        }
        
        $query = $this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
    }

    function get_chart_value_allyear($portal, $interval, $kata, $date, $type, $sentiment)
    {
        $portal = implode(',', $portal);
        for($i = 0; $i < count($kata); $i++)
        {
            $kata[$i] = "'".$kata[$i]."'";
        }
        $kata = implode(', ', $kata);
        
        $query = $this->db->query('select sum('.$sentiment.') as score from aggregation_value av join aggregation ag on av.aggregation_id = ag.aggregation_id join document doc on doc.document_id = av.document_id where '.$type.'(doc.date_published) = '.$date.' AND ag.aggregation_word IN ('.$kata.') AND doc.portal_id IN ('.$portal.');');
        
        if(is_null($query->row('score')))
            return 0;
        return $query->row('score');
    }
    function get_data_score_allyear($portal, $kata)
    {
        $this->db->select('*');
        $this->db->from('document doc');
        $this->db->join('portals por', 'por.portal_id = doc.portal_id');
        $this->db->join('document_links lks', 'doc.link_id = lks.link_id');
        $this->db->where_in('doc.portal_id', $portal);
        for($i = 0; $i < count($kata); $i++)
        {
            if($i == 0)
                $this->db->like('doc.kataPenting', '"'.$kata[$i].'"');
            else
                $this->db->or_like('doc.kataPenting', '"'.$kata[$i].'"');
        }
        
        $query = $this->db->get();
        // print_r($this->db->last_query());
        return $query->result_array();
    }

    function get_year()
    {
        $query = $this->db->query('select distinct year(date_published) as annual from document order by annual;');
        $dt = $query->result_array();
        $i = 0; $year = array();
        foreach($dt as $data)
        {
            $year[$i] = $data['annual'];
            $i++;
        }
        return $year;
    }

    function set_data_series($data)
    {
        $i = 0;
        foreach($data as $value)
        {
            //[Date.UTC(1970,  9, 27), 0   ]
            $date = explode('-', $value['date_published']);
            $series[$i] = "[Date.UTC(".$date[0].", ".$date[1].", ".$date[2]."), ".$value['c_value']."]";
            $s[$i] = $value['c_value'];
            $i++;
        }
        return $series;
    }

    //TREND NEWS MODEL
    function get_total_news_byYear($year, $portal = NULL, $keyword = NULL, $sentiment = NULL)
    {
        if(!is_null($keyword))
            $keyword = implode(' OR kataPenting like ', $keyword);

        $portal = implode(',', $portal);
        if($sentiment == 1)
            $sentiment_query = 'AND c_value > 0';
        elseif($sentiment == 2)
            $sentiment_query = 'AND c_value < 0';
        else
            $sentiment_query = '';
        if(!is_null($keyword))
            $query = $this->db->query('select * from document where year(date_published) = '.$year.' AND portal_id in ('.$portal.') '.$sentiment_query.' AND ( kataPenting like '.$keyword.');');
        else
            $query = $this->db->query('select * from document where year(date_published) = '.$year.' AND portal_id in ('.$portal.') '.$sentiment_query.';');
        
        return $query->num_rows();
    }
    function get_total_news_byMonth($year, $month, $portal = NULL, $keyword = NULL, $sentiment = NULL)
    {
        if(!is_null($keyword))
            $keyword = implode(' OR kataPenting like ', $keyword);
        
        $portal = implode(',', $portal);
        if($sentiment == 1)
            $sentiment_query = 'AND c_value > 0';
        elseif($sentiment == 2)
            $sentiment_query = 'AND c_value < 0';
        else
            $sentiment_query = '';
        if(!is_null($keyword))
            $query = $this->db->query('select * from document where year(date_published) = '.$year.' AND month(date_published) = '.$month.' AND portal_id in ('.$portal.') '.$sentiment_query.' AND ( kataPenting like '.$keyword.');');
        else
            $query = $this->db->query('select * from document where year(date_published) = '.$year.' AND month(date_published) = '.$month.' AND portal_id in ('.$portal.') '.$sentiment_query.';');
        return $query->num_rows();
    }

    function get_data_news_trend($interval, $portal = NULL, $keyword = NULL, $sentiment = NULL)
    {
        if(!is_null($keyword))
            $keyword = implode(' OR doc.kataPenting like ', $keyword);

        $portal = implode(',', $portal);

        if($sentiment == 1)
            $sentiment_query = 'AND doc.c_value > 0';
        elseif($sentiment == 2)
            $sentiment_query = 'AND doc.c_value < 0';
        else
            $sentiment_query = '';

        if(!is_null($keyword))
            $query = $this->db->query('select doc.document_id, doc.date_published, doc.document_title, doc.kataPenting, por.name, lks.link from document doc JOIN document_links lks ON lks.link_id = doc.link_id JOIN portals por ON por.portal_id = doc.portal_id where doc.portal_id in ('.$portal.') '.$sentiment_query.' AND ( doc.kataPenting like '.$keyword.');');
        else
            $query = $this->db->query('select doc.document_id, doc.date_published, doc.document_title, doc.kataPenting, por.name, lks.link from document doc JOIN document_links lks ON lks.link_id = doc.link_id JOIN portals por ON por.portal_id = doc.portal_id where doc.portal_id in ('.$portal.') '.$sentiment_query.';');
        
        
        return $query->result_array();
    }

    function get_data_news_trend_withYear($interval, $portal = NULL, $keyword = NULL, $sentiment = NULL, $year = NULL)
    {
        if(!is_null($keyword))
            $keyword = implode(' OR kataPenting like ', $keyword);

        $portal = implode(',', $portal);

        if($sentiment == 1)
            $sentiment_query = 'AND c_value > 0';
        elseif($sentiment == 2)
            $sentiment_query = 'AND c_value < 0';
        else
            $sentiment_query = '';

        if(!is_null($keyword))
            $query = $this->db->query('select doc.document_id, doc.date_published, doc.document_title, doc.kataPenting, por.name, lks.link from document doc JOIN document_links lks ON lks.link_id = doc.link_id JOIN portals por ON por.portal_id = doc.portal_id from document JOIN document_links lks ON lks.link_id = doc.link_id  where year(date_published) = '.$year.' AND portal_id in ('.$portal.') '.$sentiment_query.' AND ( kataPenting like '.$keyword.');');
        else
            $query = $this->db->query('select doc.document_id, doc.date_published, doc.document_title, doc.kataPenting, por.name, lks.link from document doc JOIN document_links lks ON lks.link_id = doc.link_id JOIN portals por ON por.portal_id = doc.portal_id from document JOIN document_links lks ON lks.link_id = doc.link_id  where year(date_published) = '.$year.' AND portal_id in ('.$portal.') '.$sentiment_query.';');

        return $query->result_array();
    }    
}