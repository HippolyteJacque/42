using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Weapon : MonoBehaviour {

	public int ammo;
	private bool isPickedUp;
	public float range;

	public GameObject AmmoDisplay;


	// Use this for initialization
	void Start () {

	}
	
	// Update is called once per frame
	void Update () {

		if (gameObject.activeSelf && gameObject.tag != "enemy" && gameObject.name == "saber"){
			AmmoDisplay.GetComponent<Text>().text = "∞";
		}
		else if (gameObject.activeSelf && gameObject.tag != "enemyWP"){
			AmmoDisplay.GetComponent<Text>().text = ammo.ToString();
		}
	}
}
