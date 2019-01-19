using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ToolTipSpell : MonoBehaviour {

	public CanvasGroup mySpellCanvas;
	//public TextMesh mySpellTip;
	//public GameObject mySpell;

 	public void DisplaySpellTip(){
 		mySpellCanvas.alpha = 1f;
 	}

 	public void HideSpellTip(){
		mySpellCanvas.alpha = 0f;
 	}
}
