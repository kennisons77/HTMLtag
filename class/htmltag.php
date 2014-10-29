<?php
/**
 * A simple class for generating nested and formatted html tags
 *
 *
 * Variables:
 * $tag: the tag being created or added
 * $attributes: an array of Possible attributes: are id, class, href, title, text, alt, target, style, function, media_type, downlaod.
 * $content: can be null, a string or tag object
 *
 * $selfclosers: tags that don't require a closing link
 * $single_attributes: html attributes that dont include a delcared value
 *
 * $display: formatting method for element, can be block, newline or inline
 *
 * unicode character for code formatting CHR(13) . CHR(10) . CHR(9)
 *
 * unicode characters for tab CHR(11)
 *
 */

class HtmlTag {

    private static $_instance = null;

    private $tag = null;
    private $attributes = null;
    private $content = null;

    private $selfclosers = array('area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr');
    private $single_attributes = array(
        'function',
        'download');

    private $display;//block, newline, inline

    function __construct($tag, $content='',$attributes=''){
        $this->tag = strtolower($tag);
        if (!empty($content)) $this->content = $content;
        if (!empty($attributes)) $this->attributes = $attributes;

        return $this;
    }

    public static function mTag($tag, $content='',$attributes=''){
        self::$_instance = new HtmlTag($tag,$content,$attributes);
        return self::$_instance;
    }

    public function aTag ($tag){
        $htmlTag = null;
        if(is_null($this->content)){
            $this->content = array();
        }
        if(is_object($tag) && get_class($tag) == get_class($this)){
            $htmlTag = $tag;
        }
        else{
            $htmlTag = new HtmlTag($tag);
            $this->content[] = $htmlTag;
        }
        return $htmlTag;
    }
    /**
     * Sets single attributes
     *
     * Creates any necessary attributes one at a time, value can be blank in case of download or function
     */
    public function set($property,$value=''){
        if(is_null($this->attributes)) $this->attributes = array();
        $this->attributes[$property] = $value;
        return $this;
    }

    /**
     * Convert tag object to a string
     *
     * Convert an HTMLTag object into a string starting with innermost nested comment first
     */
    function generate($tag,$attributes){

    }
    function generate_list($tag,$content){
        $list=array();

    }

    public function __toString() {
        return $this->pTag();
    }

    public function pTag(){
        $string = '';
        if(!empty($this->tag)){
            $string .=  '<' . $this->tag;
            if (!is_null($this->attributes)) $string .= $this->$tag->pAttributes;
            if (in_array($this->tag,$this->selfclosers)){
                $string .= '/>' .CHR(13) . CHR(10) . CHR(9);
            }else{
                $string .= '>' . $this->pContent(). '</' . $this->tag . '>';
            }
        }
        return $string;
    }

    private function pAttributes(){
        $string = '';
            foreach($this->attributes as $key => $value){
                if(!empty($value)){
                    $string .= ' ' . $key . '="' . $value . '"';
                }else{
                    $string .= ' ' . $key;
                }
            }
        return substr($string, 1);
    }

    private function pContent(){
        $string = '';
        if(!is_null($this->content)){
            if(is_object($this->content) && get_class($this->content) == get_class($this)){
                $string .=  $this->aTag($this->content);
            }else{
                $string .= $this->content. CHR(13) . CHR(10) . CHR(9);
            }
        }
        return $string;
    }
}