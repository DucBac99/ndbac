<?php

/**
 * Get the parameter value of the active theme. 
 * With the add of this function, following functions deprecated: 
 *     - active_theme_path(); 
 *     - active_theme_url();
 *     
 * @param  string $param Name of the parameter [idname, path, url]
 * @since v4.1
 * @return string|null         
 */
function active_theme($param)
{
  $AuthSite = $GLOBALS['AuthSite'];
  // Idname of the active theme
  if ($param == "idname") {
    if (!$AuthSite) {
      return "";
    }
    return $AuthSite->get("theme");
  }


  // Absolute path of the root directory of the active theme
  if ($param == "path") {
    return THEMES_PATH . "/" . active_theme("idname");
  }


  // URI of root directory the active theme
  if ($param == "url" || $param == "uri") {
    return THEMES_URL . "/" . active_theme("idname");
  }

  // Invalid parameter, return null
  return null;
}
