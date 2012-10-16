<?php

Class NotificationParser {

  public static function parse($notification) {
    $return_array = array();

    if ($notification->notification_type == "Dismissal") {
      $bid = $notification->payload["bid"];
      $return_array["subject"] = "Your bid on ".$bid["project"]["title"]." has been dismissed.";
      $return_array["line1"] = "Your bid on <a href='".route('bid', array($bid["project"]["id"], $bid["id"]))."'>".$bid["project"]["title"].
                "</a> has been dismissed.";
      $return_array["line2"] = "Dismissal reason: \"" . $notification->payload["bid"]["dismissal_reason"]."\"";
      $return_array["link"] = route('bid', array($bid["project"]["id"], $bid["id"]));

    } elseif ($notification->notification_type == "Undismissal") {
      $bid = $notification->payload["bid"];
      $return_array["subject"] = "Your bid on ".$bid["project"]["title"]." has been un-dismissed.";
      $return_array["line1"] = "Your bid on <a href='".route('bid', array($bid["project"]["id"], $bid["id"]))."'>".$bid["project"]["title"].
                "</a> has been un-dismissed.";
      $return_array["line2"] = "Congrats, you're back in the running!";
      $return_array["link"] = route('bid', array($bid["project"]["id"], $bid["id"]));

    } elseif ($notification->notification_type == "BidSubmit") {
      $bid = $notification->payload["bid"];
      $return_array["subject"] = $bid["vendor"]["company_name"]." has submitted a bid for ".$bid["project"]["title"].".";
      $return_array["line1"] = $bid["vendor"]["company_name"]." has <a href='".route('bid', array($bid["project"]["id"], $bid["id"])).
                "'>submitted a bid</a> for ".$bid["project"]["title"].".";
      $return_array["line2"] = Helper::truncate($bid["approach"], 20);
      $return_array["link"] = route('bid', array($bid["project"]["id"], $bid["id"]));

    } elseif ($notification->notification_type == "ProjectCollaboratorAdded") {
      $project = $notification->payload["project"];
      $return_array["subject"] = "You have been added as a collaborator for ".$project["title"].".";
      $return_array["line1"] = "You have been added as a collaborator on  <a href='".route('project', array($project["id"]))."'>".$project["title"]."</a>.";
      $return_array["line2"] = "You can now review bids and answer questions about this project.";
      $return_array["link"] = route('project', array($project["id"]));

    }

    $return_array["timestamp"] = date('c', is_object($notification->created_at) ? $notification->created_at->getTimestamp() : strtotime($notification->created_at));
    return $return_array;
  }

}