using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class InventoryTab : MonoBehaviour {

	public Canvas SpellsCanvas;
	public CanvasGroup GameHUD;
	public CanvasGroup EnemyCanvas;
	public Canvas InventoryCanvas;

	// Use this for initialization
	void Start () {
		InventoryCanvas.enabled = false;
	}
	
	// Update is called once per frame
	void Update () {
		if (Input.GetKeyDown("i")){
			Show();
		}
	}

	public void Show(){
		if (InventoryCanvas.enabled == false){
			InventoryCanvas.enabled = true;
			GameHUD.alpha = 0f;
			EnemyCanvas.alpha = 0f;
			SpellsCanvas.enabled = false;
		}
		else{
			InventoryCanvas.enabled = false;
			GameHUD.alpha = 1f;
		}
	}
}
