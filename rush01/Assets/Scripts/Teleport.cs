using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class Teleport : MonoBehaviour {

	private int sceneID;	

	// Use this for initialization
	void Start () {
		sceneID = SceneManager.GetActiveScene().buildIndex;
	}
	
	void OnTriggerEnter(Collider coll){
		if (coll.gameObject.tag == "Player"){
			SceneManager.LoadScene(sceneID+1);
		}
	}
}
